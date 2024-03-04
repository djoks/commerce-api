<?php

namespace App\Http\Requests;

use App\Rules\DiscountCheckRule;
use App\Rules\StockCheckRule;
use Illuminate\Foundation\Http\FormRequest;

class CheckoutRequest extends FormRequest
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
        return [
            'customer_id' => 'required|exists:users,id',
            'billing_id' => 'required|exists:billings,id',
            'items' => 'required|array',
            'items.*.product_id' => ['required', 'exists:products,id', new StockCheckRule()],
            'items.*.quantity' => 'required|integer|min:1',
            'discounts' => ['nullable', 'array', new DiscountCheckRule()],
            'shipping' => 'required',
            'shipping.first_name' => 'required|string',
            'shipping.last_name' => 'required|string',
            'shipping.street_address' => 'required|string',
            'shipping.city' => 'required|string',
            'shipping.state' => 'required|string',
            'shipping.country' => 'required|string',
            'payment_type_id' => 'required|exists:payment_types,id',
            'msisdn' => 'required_if:payment_type_id,3',
            'phone_number' => 'required_if:payment_type_id,3',
        ];
    }
}
