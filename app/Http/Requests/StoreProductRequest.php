<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Otorisasi di-handle oleh middleware route
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $productId = $this->route('product')?->id;

        return [
            'name'                        => ['required', 'string', 'max:255'],
            'sku'                         => ['required', 'string', 'max:50', Rule::unique('products')->ignore($productId)],
            'price'                       => ['required', 'numeric', 'min:0', 'max:99999999.99'],
            'category'                    => ['required', 'string', 'max:255'],
            'is_active'                   => ['nullable', 'boolean'],
            'image'                       => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'ingredients'                 => ['required', 'array', 'min:1'],
            'ingredients.*.ingredient_id' => ['required', 'exists:ingredients,id'],
            'ingredients.*.quantity'      => ['required', 'numeric', 'gt:0', 'max:999999.99'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required'                        => 'Nama produk wajib diisi.',
            'name.string'                          => 'Nama produk harus berupa teks.',
            'name.max'                             => 'Nama produk maksimal 255 karakter.',
            'sku.required'                         => 'Kode SKU wajib diisi.',
            'sku.string'                           => 'Kode SKU harus berupa teks.',
            'sku.max'                              => 'Kode SKU maksimal 50 karakter.',
            'sku.unique'                           => 'Kode SKU sudah digunakan oleh produk lain.',
            'price.required'                       => 'Harga produk wajib diisi.',
            'price.numeric'                        => 'Harga produk harus berupa angka.',
            'price.min'                            => 'Harga produk tidak boleh kurang dari 0.',
            'category.required'                    => 'Kategori produk wajib diisi.',
            'category.string'                      => 'Kategori produk harus berupa teks.',
            'category.max'                         => 'Kategori produk maksimal 255 karakter.',
            'ingredients.required'                 => 'Resep (bahan baku) wajib diisi.',
            'ingredients.array'                    => 'Resep harus berupa array bahan baku.',
            'ingredients.min'                      => 'Resep harus memiliki minimal 1 bahan baku.',
            'ingredients.*.ingredient_id.required' => 'Bahan baku wajib dipilih.',
            'ingredients.*.ingredient_id.exists'   => 'Bahan baku yang dipilih tidak valid.',
            'ingredients.*.quantity.required'      => 'Takaran bahan baku wajib diisi.',
            'ingredients.*.quantity.numeric'       => 'Takaran bahan baku harus berupa angka.',
            'ingredients.*.quantity.gt'            => 'Takaran bahan baku harus lebih dari 0.',
        ];
    }
}
