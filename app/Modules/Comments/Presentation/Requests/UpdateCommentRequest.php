<?php

namespace App\Modules\Comments\Presentation\Requests;

use App\Rules\NoHtmlTags;
use Illuminate\Foundation\Http\FormRequest;

class UpdateCommentRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
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
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'text.required' => __('comments::validation.text_required'),
            'text.string' => __('comments::validation.text_string'),
            'text.min' => __('comments::validation.text_min'),
            'text.max' => __('comments::validation.text_max'),
        ];
    }
}
