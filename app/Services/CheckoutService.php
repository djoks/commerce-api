<?php

namespace App\Services;

use Throwable;
use App\Traits\Utils;
use App\Models\Order;
use App\Traits\Payable;
use App\Models\Discount;
use App\Models\Product;
use App\Models\ApiResponse;
use App\Traits\Broadcastable;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\OrderResource;
use App\Models\PaymentType;

/**
 * Provides checkout services for processing orders, including calculating totals, handling discounts, and managing payment methods.
 */
class CheckoutService extends BaseService
{
    use Utils, Payable, Broadcastable;

    /**
     * @var string The model this service pertains to.
     */
    protected $model = Order::class;

    /**
     * @var string The resource class used for transforming order models into standardized API responses.
     */
    protected $resource = OrderResource::class;

    /**
     * Processes the checkout operation, creating orders, handling item stock, applying discounts, and initiating payment.
     *
     * @param mixed $payload Data necessary for completing the checkout process.
     * @return ApiResponse Returns ApiResponse with order and payment details on success, or error message on failure.
     */
    public function checkout(mixed $payload)
    {
        try {
            DB::beginTransaction();
            $subtotal = 0;
            $discount = 0;
            $tax = 0;
            $total = 0;

            // Create order
            $order = $this->model::create([
                'customer_id' => $payload->customer_id,
                'billing_id' => $payload->billing_id,
                'code' => $this->generateOrderNo(),
                'sub_total' => $subtotal,
                'discount' => $discount,
                'tax' => $tax,
                'total' => $total,
                'due_at' => now()->addDays(7),
            ]);

            $paymentType = PaymentType::find($payload->payment_type_id);

            foreach ($payload->items as $item) {
                $item = (object) $item;

                $product = Product::withAvailableStock()->whereId($item->product_id)->first();
                $availableStock = $product->stock;

                $quantity = $item->quantity;

                foreach ($availableStock as $stock) {
                    $price = $product->selling_price;

                    $order->items()->create([
                        'product_id' => $product->id,
                        'quantity' => $stock->id,
                        'price' => $price,
                    ]);

                    $total += $price;

                    $stock->available_quantity -= $quantity;

                    if ($stock->available_quantity < 0) {
                        $quantity -= $stock->available_quantity;
                        $stock->available_quantity = 0;
                    } else {
                        $quantity = 0;
                    }

                    $stock->save();
                }
            }

            $subtotal = $total;

            if (isset($payload->discounts)) {
                $discounts = Discount::whereIn('id', $payload->discounts)->get();

                foreach ($discounts as $discount) {
                    $total = $discount->applyDiscount($total);
                }

                $order->update([
                    'metadata' => [
                        'discounts' => $payload->discounts,
                    ],
                ]);
            }

            $order->update([
                'discount' => $subtotal - $total,
                'sub_total' => $subtotal,
                'total' => $total,
            ]);

            // Create payment
            if ($paymentType->name === 'Cash') {
                $this->payWithCash($order);
            } else {
                $this->payWithMomo($order, $payload->phone, $payload->msisdn);
            }

            $order = $order->fresh()->load([
                'items',
                'customer',
                'payment',
                'items.order',
                'items.product',
            ]);

            $payment = optional($order->payment()) ?? null;

            DB::commit();

            return new ApiResponse('Checkout successful', 200, [
                'payment' => $payment,
                'order' => $this->resource::make($order),
            ]);
        } catch (Throwable $e) {
            logger($e->getMessage() . ' ' . $e->getLine() . ' ' . $e->getFile());
            DB::rollBack();

            return new ApiResponse('Checkout was unsuccessful!', 500);
        }
    }

    /**
     * Handles OTP verification for order payment processing.
     *
     * @param mixed $invoiceId The ID of the invoice for which the OTP is to be verified.
     * @param mixed $payload Data including the OTP for verification.
     * @return ApiResponse Returns ApiResponse indicating the result of the OTP verification process.
     */
    public function checkoutOtp($invoiceId, mixed $payload)
    {
        try {
            $order = $this->model::find($invoiceId);
            $payment = $order->payment();
            $reference = $payment->reference;

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
