<?php

namespace App\Modules\Users\Presentation\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => __('users::validation.name_required'),
            'name.max' => __('users::validation.name_max'),
            'email.required' => __('users::validation.email_required'),
            'email.email' => __('users::validation.email_email'),
            'email.unique' => __('users::validation.email_unique'),
            'password.required' => __('users::validation.password_required'),
            'password.min' => __('users::validation.password_min'),
            'password.confirmed' => __('users::validation.password_confirmed'),
        ];
    }
}
