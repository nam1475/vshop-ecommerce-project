<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class AdminProductRequest extends FormRequest
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
            'name' => ['required', 'string'],
            'brand_id' => ['required'],
            'category_id' => ['required'],
            'price' => ['required', 'numeric', 'min:0'],
            'in_stock' => ['required'],
            'published' => ['required'],
            'quantity' => ['required', 'integer', 'min:1'],
            'description' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Product name is required.',
            'brand_id.required' => 'Brand is required.',
            'category_id.required' => 'Category is required.',
            'price.required' => 'Price is required.',
            'price.min' => 'Price must be greater than or equal to 0.',
            'in_stock.required' => 'In stock is required.',
            'published.required' => 'Published is required.',
            'quantity.required' => 'Quantity is required.',
            'quantity.min' => 'Quantity must be greater than or equal to 1.',
            'description.required' => 'Description is required.',
        ];
    }
}
