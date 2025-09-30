<?php

namespace App\Modules\Comments\Presentation\Requests;

use App\Rules\NoHtmlTags;
use Illuminate\Foundation\Http\FormRequest;

class CreateCommentRequest extends FormRequest
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
            'text' => ['required', 'string', 'max:2000', new NoHtmlTags],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'text.required' => __('comments::validation.text_required'),
        ];
    }
}
