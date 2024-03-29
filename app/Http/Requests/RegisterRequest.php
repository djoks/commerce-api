<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class RegisterRequest extends FormRequest
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
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|unique:users,phone|size:10',
            'password' => 'required|confirmed'
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'user.required' => 'An email address or phone number is required',
            'user.email' => 'The email must be a valid email address',
            'user.size' => 'The phone number must be 10 digits',
            'user.exists' => $this->isEmail() ? 'The email address is invalid' : 'The phone number is invalid',
            'password.required' => 'A password is required',
        ];
    }

    private function isEmail(): bool
    {
        return filter_var($this->user, FILTER_VALIDATE_EMAIL);
    }
}
