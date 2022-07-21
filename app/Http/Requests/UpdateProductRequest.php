<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'price' => ['required', 'double'],
            'old_price' => ['nullable', 'double'],
            'stock' => ['required', 'integer'],
            'tags' => ['array'],
            'description' => ['required', 'string'],
            'additional_information' => ['array'],
            'category' => [
                'required',
                'string',
                Rule::exists('categories', 'name')
            ]
        ];
    }
}
