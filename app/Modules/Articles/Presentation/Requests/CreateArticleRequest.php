<?php

namespace App\Modules\Articles\Presentation\Requests;

use App\Rules\NoHtmlTags;
use Illuminate\Foundation\Http\FormRequest;

class CreateArticleRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255', new NoHtmlTags],
            'content' => ['required', 'string', 'max:10000', new NoHtmlTags],
            'author_id' => 'required|integer|exists:users,id',
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => __('articles::validation.title_required'),
            'title.max' => __('articles::validation.title_max'),
            'content.required' => __('articles::validation.content_required'),
            'author_id.required' => __('articles::validation.author_required'),
            'author_id.exists' => __('articles::validation.author_exists'),
        ];
    }
}
