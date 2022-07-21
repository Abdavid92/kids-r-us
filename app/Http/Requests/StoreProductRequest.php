<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
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
            'price' => ['required', 'numeric'],
            'old_price' => ['nullable', 'numeric'],
            'stock' => ['required', 'integer'],
            'tags' => ['array'],
            'description' => ['required', 'string'],
            'additional_information' => ['array'],
            'photo' => ['nullable', 'image', 'max:2048'],
            'category' => [
                'required',
                'string',
                Rule::exists('categories', 'name')
            ]
        ];
    }
}
