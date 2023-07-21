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
        // if payment_method is momo, then the request should have a phone and provider
        return [
            'client_id' => 'required|exists:clients,id',
            'items' => 'required|array',
            'items.*.equipment_id' => ['required', 'exists:equipment,id', new StockCheckRule()],
            'items.*.quantity' => 'required|integer|min:1',
            'lease' => 'nullable|array',
            'lease.start_date' => 'nullable|date',
            'lease.end_date' => 'nullable|date|after:lease.start_date',
            'payment_method' => 'required|string|in:cash,momo',
            'discounts' => ['nullable', 'array', new DiscountCheckRule()],

            'phone' => 'required_if:payment_method,momo',
            'network' => 'required_if:payment_method,momo',
        ];
    }
}
