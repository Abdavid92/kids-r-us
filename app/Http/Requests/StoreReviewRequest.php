<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreReviewRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'assessment' => ['required', 'integer', 'min:1', 'max:5'],
            'comment' => ['required', 'string'],
            'product_id' => [
                'required',
                'integer',
                Rule::exists('products', 'id')
            ]
        ];
    }
}
