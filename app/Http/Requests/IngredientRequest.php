<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class IngredientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Otorisasi sudah di-handle middleware route
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $ingredientId = $this->route('ingredient')?->id;

        return [
            'name'         => ['required', 'string', 'max:255'],
            'sku'          => ['required', 'string', 'max:50', Rule::unique('ingredients')->ignore($ingredientId)],
            'stock'        => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'unit'         => ['required', 'string', 'in:gram,kg,ml,liter,pcs'],
            'safety_stock' => ['required', 'numeric', 'min:0', 'max:999999.99'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required'         => 'Nama bahan baku wajib diisi.',
            'sku.required'          => 'Kode SKU wajib diisi.',
            'sku.unique'            => 'Kode SKU sudah digunakan.',
            'sku.max'               => 'Kode SKU maksimal 50 karakter.',
            'stock.required'        => 'Stok wajib diisi.',
            'stock.min'             => 'Stok tidak boleh negatif.',
            'stock.numeric'         => 'Stok harus berupa angka.',
            'unit.required'         => 'Satuan wajib dipilih.',
            'unit.in'               => 'Satuan harus salah satu dari: gram, kg, ml, liter, pcs.',
            'safety_stock.required' => 'Safety stock wajib diisi.',
            'safety_stock.min'      => 'Safety stock tidak boleh negatif.',
            'safety_stock.numeric'  => 'Safety stock harus berupa angka.',
        ];
    }
}
