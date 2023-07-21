<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'sub_total' => $this->sub_total,
            'tax' => $this->tax,
            'discount' => $this->discount,
            'total' => $this->total,
            'items' => OrderItemResource::collection($this->items),
            'billing' => new BillingResource($this->billing),
            'payment' => new PaymentResource($this->payment),
            'shipping' => $this->shipping,
            'meta' => $this->meta
        ];
    }
}
