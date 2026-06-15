<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePurchaseOrderStatusRequest extends FormRequest
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
        $rules = [
            'status' => ['required', 'in:draft,sent,on_delivery,completed,cancelled'],
        ];

        if ($this->input('status') === 'completed') {
            $rules['received_date'] = ['required', 'date'];
            $rules['ingredients'] = ['required', 'array', 'min:1'];
            $rules['ingredients.*.ingredient_id'] = ['required', 'exists:ingredients,id'];
            $rules['ingredients.*.quantity_received'] = ['required', 'numeric', 'min:0', 'max:999999.99'];
        }

        return $rules;
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'status.required'                          => 'Status wajib diisi.',
            'status.in'                                => 'Status yang dipilih tidak valid.',
            'received_date.required'                   => 'Tanggal terima wajib diisi untuk status selesai (completed).',
            'received_date.date'                       => 'Format tanggal terima tidak valid.',
            'ingredients.required'                     => 'Data bahan baku wajib disertakan.',
            'ingredients.array'                        => 'Data bahan baku harus berupa array.',
            'ingredients.min'                          => 'Minimal harus ada 1 data bahan baku yang diterima.',
            'ingredients.*.ingredient_id.required'     => 'Bahan baku wajib diisi.',
            'ingredients.*.ingredient_id.exists'       => 'Bahan baku yang dipilih tidak valid.',
            'ingredients.*.quantity_received.required' => 'Jumlah diterima wajib diisi.',
            'ingredients.*.quantity_received.numeric'  => 'Jumlah diterima harus berupa angka.',
            'ingredients.*.quantity_received.min'      => 'Jumlah diterima tidak boleh kurang dari 0.',
        ];
    }
}
