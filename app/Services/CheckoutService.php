<?php

namespace App\Services;

use App\Http\Resources\InvoiceResource;
use App\Models\ApiResponse;
use App\Models\Discount;
use App\Models\Equipment;
use App\Models\Invoice;
use App\Traits\Notifiable;
use App\Traits\Payable;
use App\Traits\Utils;
use Illuminate\Support\Facades\DB;
use Throwable;

class CheckoutService extends BaseService
{
    use Utils, Payable, Notifiable;

    protected $model = Invoice::class;

    protected $resource = InvoiceResource::class;

    public function checkout(mixed $payload)
    {
        try {
            DB::beginTransaction();
            $isLease = !is_null($payload->lease);
            $subtotal = 0;
            $discount = 0;
            $tax = 0;
            $total = 0;

            // Create invoice
            $invoice = $this->model::create([
                'client_id' => $payload->client_id,
                'branch_id' => auth()->user()->branch_id,
                'creator_id' => auth()->id(),
                'number' => $this->generateInvoiceNo(),
                'sub_total' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total' => $total,
                'due_at' => now()->addDays(7),
            ]);

            // Create lease if it is a lease
            if ($isLease) {
                $lease = (object) $payload->lease;

                $invoice->lease()->create([
                    'client_id' => $payload->client_id,
                    'starts_at' => $lease->start_date,
                    'ends_at' => $lease->end_date,
                ]);
            }

            foreach ($payload->items as $item) {
                $item = (object) $item;

                $equipment = Equipment::find($item->equipment_id);
                $availableStock = $equipment->availableStocks();
                $quantityRequired = $item->quantity;

                $choosenStocks = $availableStock->take($quantityRequired)->get();

                foreach ($choosenStocks as $stock) {
                    $price = $isLease ? $equipment->lease_price * $invoice->lease->days : $equipment->selling_price;
                    $status = $isLease ? 'Leased' : 'Sold';

                    $stock->status()->transitionTo($status);
                    $invoice->items()->create([
                        'equipment_id' => $equipment->id,
                        'equipment_stock_id' => $stock->id,
                        'price' => $price,
                    ]);

                    $total += $price;
                }
            }

            $subtotal = $total;

            if (isset($payload->discounts)) {
                $discounts = Discount::whereIn('id', $payload->discounts)->get();

                foreach ($discounts as $discount) {
                    $total = $discount->applyDiscount($total);
                }

                $invoice->update([
                    'metadata' => [
                        'discounts' => $payload->discounts,
                    ],
                ]);
            }

            $invoice->update([
                'discount' => $subtotal - $total,
                'sub_total' => $subtotal,
                'total' => $total,
            ]);

            // Create payment
            if ($payload->payment_method === 'cash') {
                $this->payWithCash($invoice);
            } else {
                $this->payWithMomo($invoice, $payload->phone, $payload->network);
            }
            $invoice = $invoice->fresh()->load([
                'items',
                'client',
                'payments',
                'lease',
                'items.equipment',
                'items.equipmentStock',
            ]);

            $payment = optional($invoice->payments())->first() ?? null;

            DB::commit();

            $this->notifyItemSold($invoice);
            (new InventoryTrackerService())->doChecks();

            return new ApiResponse('Checkout successful', 200, [
                'payment' => $payment,
                'invoice' => $this->resource::make($invoice),
            ]);
        } catch (Throwable $e) {
            logger($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            DB::rollBack();

            return new ApiResponse('Checkout was unsuccessful!', 500);
        }
    }

    public function checkoutOtp($invoiceId, mixed $payload)
    {
        try {
            $invoice = $this->model::find($invoiceId);
            $payment = $invoice->payments()->first();
            $reference = $payment->transaction_id;

            $params = (object) [
                'reference' => $reference,
                'otp' => $payload->otp,
            ];

            $response = $this->submitOtp($params);

            return new ApiResponse('OTP Response', 200, $response);
        } catch (Throwable $th) {
            return new ApiResponse('Sorry, something went wrong', 500);
        }
    }
}
