<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePharmacyRequest extends FormRequest
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
            'name' => 'required|string|max:255|min:3',
            'address' => 'required|string|max:500|min:10',
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
            'name.required' => 'Pharmacy name is required.',
            'name.min' => 'Pharmacy name must be at least 3 characters.',
            'name.max' => 'Pharmacy name cannot exceed 255 characters.',
            'address.required' => 'Pharmacy address is required.',
            'address.min' => 'Address must be at least 10 characters.',
            'address.max' => 'Address cannot exceed 500 characters.',
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
            'name' => 'pharmacy name',
            'address' => 'pharmacy address',
        ];
    }
}