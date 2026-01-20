<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
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
            'title' => 'nullable|string|max:255',
            'description' => 'nullable|string|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
            'price' => 'nullable|numeric|min:0|max:999999.99',
            'quantity' => 'nullable|integer|min:0|max:999999',
        ];
    }

    /**
     * Get custom error messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'title.required' => 'Product title is required.',
            'title.max' => 'Product title cannot exceed 255 characters.',
            'description.max' => 'Description cannot exceed 1000 characters.',
            'image.image' => 'The file must be an image.',
            'image.mimes' => 'Image must be a file of type: jpeg, png, jpg, gif, webp.',
            'image.max' => 'Image size cannot exceed 2MB.',
            'price.required' => 'Price is required.',
            'price.numeric' => 'Price must be a valid number.',
            'price.min' => 'Price must be at least 0.',
            'price.max' => 'Price cannot exceed 999,999.99.',
            'quantity.required' => 'Quantity is required.',
            'quantity.integer' => 'Quantity must be a whole number.',
            'quantity.min' => 'Quantity must be at least 0.',
            'quantity.max' => 'Quantity cannot exceed 999,999.',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'title' => 'product title',
            'description' => 'product description',
            'image' => 'product image',
            'price' => 'product price',
            'quantity' => 'product quantity',
        ];
    }
}