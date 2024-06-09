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
            'nama_product' => 'required|string|max:255',
            'description' => 'string|max:255',
            'stock' => 'integer',
            'price' => 'integer',
            'image' => 'image|mimes:jpeg,png,jpg,webp|max:2048',
            'id_category' => 'integer',
            'id_ukuran' => 'integer',
        ];
    }
}
