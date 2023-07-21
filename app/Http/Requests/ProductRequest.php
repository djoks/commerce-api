<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array|string>
     */
    public function rules(): array
    {
        $rules = [
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string',
            'barcode' => 'required|unique:products,barcode|string',
            'cost_price' => 'required|numeric',
            'selling_price' => 'required|numeric',
            'notes' => 'nullable|string',
            'photo' => 'nullable|image|mimes:jpg,jpeg,png',
        ];

        if ($this->isMethod('PATCH')) {
            $productId = $this->route('product');
            $rules['barcode'] .= ',' . $productId;
        }

        return $rules;
    }
}
