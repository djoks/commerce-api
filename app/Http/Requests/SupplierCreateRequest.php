<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierCreateRequest extends FormRequest
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
            'name' => 'required|string',
            'phone' => 'required|size:10',
            'email' => 'required|email',
            'address' => 'nullable|string',
            'contact_person_name' => 'required|string',
            'contact_person_phone' => 'required|size:10',
            'contact_person_email' => 'required|email',
        ];
    }
}
