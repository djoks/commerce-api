<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'name' => 'required|string',
            'email' => 'required_without:phone|email|unique:users,email',
            'phone' => 'required_without:email|string|unique:users,phone|size:10',
            'password' => 'required|confirmed',
            'role' => 'required|string'
        ];

        if ($this->isMethod('PATCH')) {
            $id = $this->route('user');

            $rules['email'] = $rules['email'] . ',' . $id;
            $rules['phone'] = $rules['phone'] . ',' . $id;
            $rules['role'] = 'required';
        }

        return $rules;
    }
}
