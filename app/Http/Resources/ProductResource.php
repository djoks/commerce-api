<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
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
            'name' => $this->name,
            'slug' => $this->slug,
            'category' => new CategoryResource($this->category),
            'price' => $this->selling_price,
            'quantity' => $this->stock->sum('available_quantity'),
            'barcode' => $this->barcode,
            'notes' => $this->notes,
            'photo' => $this->photo,
            'status' => $this->status
        ];
    }
}
