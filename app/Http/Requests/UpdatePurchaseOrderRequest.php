<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseOrderRequest extends FormRequest
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
        return [
            'supplier_id'                  => ['required', 'exists:suppliers,id'],
            'order_date'                   => ['required', 'date'],
            'ingredients'                  => ['required', 'array', 'min:1'],
            'ingredients.*.ingredient_id'  => ['required', 'exists:ingredients,id'],
            'ingredients.*.quantity'       => ['required', 'numeric', 'gt:0', 'max:999999.99'],
            'ingredients.*.unit_price'     => ['required', 'numeric', 'min:0', 'max:99999999.99'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'supplier_id.required'                 => 'Supplier wajib dipilih.',
            'supplier_id.exists'                   => 'Supplier yang dipilih tidak valid.',
            'order_date.required'                  => 'Tanggal order wajib diisi.',
            'order_date.date'                      => 'Format tanggal order tidak valid.',
            'ingredients.required'                 => 'Bahan baku wajib diisi.',
            'ingredients.array'                    => 'Bahan baku harus berupa list.',
            'ingredients.min'                      => 'Minimal harus ada 1 bahan baku yang dipesan.',
            'ingredients.*.ingredient_id.required' => 'Bahan baku wajib dipilih.',
            'ingredients.*.ingredient_id.exists'   => 'Bahan baku yang dipilih tidak valid.',
            'ingredients.*.quantity.required'      => 'Jumlah bahan baku wajib diisi.',
            'ingredients.*.quantity.numeric'       => 'Jumlah bahan baku harus berupa angka.',
            'ingredients.*.quantity.gt'            => 'Jumlah bahan baku harus lebih besar dari 0.',
            'ingredients.*.unit_price.required'    => 'Harga satuan wajib diisi.',
            'ingredients.*.unit_price.numeric'     => 'Harga satuan harus berupa angka.',
            'ingredients.*.unit_price.min'         => 'Harga satuan tidak boleh kurang dari 0.',
        ];
    }
}
