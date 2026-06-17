<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Handle via routing middleware
    }

    /**
     * Get the validation rules that apply to the request.
     */
    public function rules(): array
    {
        return [
            'order_type'     => ['required', 'string', 'in:dine_in,takeaway,delivery'],
            'table_number'   => ['required_if:order_type,dine_in', 'nullable', 'string', 'max:50'],
            'payment_method' => ['required', 'string', 'max:100'],
            'discount'       => ['nullable', 'numeric', 'min:0'],
            'items'          => ['required', 'array', 'min:1'],
            'items.*.id'     => ['required', 'exists:products,id'],
            'items.*.qty'    => ['required', 'integer', 'min:1'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'order_type.required'     => 'Tipe pesanan wajib dipilih.',
            'order_type.in'           => 'Tipe pesanan harus berupa Dine In, Takeaway, atau Delivery.',
            'table_number.required_if'=> 'Nomor meja wajib diisi untuk tipe pesanan Dine In.',
            'table_number.max'        => 'Nomor meja maksimal 50 karakter.',
            'payment_method.required' => 'Metode pembayaran wajib dipilih.',
            'discount.numeric'        => 'Potongan harga harus berupa angka.',
            'discount.min'            => 'Potongan harga tidak boleh kurang dari 0.',
            'items.required'          => 'Keranjang belanja tidak boleh kosong.',
            'items.array'             => 'Keranjang belanja harus berupa daftar produk.',
            'items.min'               => 'Keranjang belanja minimal harus memiliki 1 produk.',
            'items.*.id.required'     => 'ID produk wajib diisi.',
            'items.*.id.exists'       => 'Produk yang dipilih tidak valid atau tidak terdaftar.',
            'items.*.qty.required'    => 'Jumlah produk wajib diisi.',
            'items.*.qty.integer'     => 'Jumlah produk harus berupa bilangan bulat.',
            'items.*.qty.min'         => 'Jumlah produk minimal 1.',
        ];
    }
}
