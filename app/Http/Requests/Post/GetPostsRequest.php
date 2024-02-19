<?php

namespace App\Http\Requests\Post;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class GetPostsRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array|string>
     */
    public function rules(): array
    {
        return [
            'id' => 'nullable|string',
            'sort' => 'nullable|string',
            'category' => 'nullable|string',
            'subCategory' => 'nullable|string',
            'country' => 'nullable|string',
            'sector' => 'nullable|string',
            'author' => 'nullable|string',
            'ticker' => 'nullable|string',
            'isin' => 'nullable|string',
            'start_date' => 'nullable|string',
            'end_date' => 'nullable|string',
            'tag' => 'nullable|string',
        ];
    }
}
