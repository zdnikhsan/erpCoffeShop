<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SupplierRequest extends FormRequest
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
        return [
            'name'          => ['required', 'string', 'max:255'],
            'phone'         => ['required', 'string', 'max:20'],
            'address'       => ['required', 'string'],
            'payment_terms' => ['required', 'integer', 'min:0'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required'          => 'Nama supplier wajib diisi.',
            'phone.required'         => 'Nomor kontak wajib diisi.',
            'address.required'       => 'Alamat wajib diisi.',
            'payment_terms.required' => 'Tempo pembayaran wajib diisi.',
            'payment_terms.min'      => 'Tempo pembayaran minimal 0 hari (COD).',
        ];
    }
}
