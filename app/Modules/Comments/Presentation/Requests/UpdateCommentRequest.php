<?php

namespace App\Modules\Comments\Presentation\Requests;

use App\Rules\NoHtmlTags;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCommentRequest extends FormRequest
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
            'text' => [
                'required',
                'string',
                'min:1',
                'max:1000',
                new NoHtmlTags,
            ],
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
            'text.required' => 'Текст комментария обязателен.',
            'text.string' => 'Текст комментария должен быть строкой.',
            'text.min' => 'Текст комментария не может быть пустым.',
            'text.max' => 'Текст комментария не может содержать более 1000 символов.',
        ];
    }
}
