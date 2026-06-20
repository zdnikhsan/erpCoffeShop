<?php

namespace App\Http\Requests;

use App\Models\Expense;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ExpenseRequest extends FormRequest
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
            'category' => ['required', 'string', Rule::in(Expense::CATEGORIES)],
            'amount'   => ['required', 'numeric', 'min:1'],
            'note'     => ['nullable', 'string', 'max:1000'],
            'date'     => ['required', 'date', 'before_or_equal:today'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'category.required'      => 'Kategori pengeluaran wajib dipilih.',
            'category.in'            => 'Kategori pengeluaran tidak valid.',
            'amount.required'        => 'Nominal pengeluaran wajib diisi.',
            'amount.numeric'         => 'Nominal pengeluaran harus berupa angka.',
            'amount.min'             => 'Nominal pengeluaran minimal Rp 1.',
            'date.required'          => 'Tanggal pengeluaran wajib diisi.',
            'date.date'              => 'Format tanggal tidak valid.',
            'date.before_or_equal'   => 'Tanggal pengeluaran tidak boleh melebihi hari ini.',
            'note.max'               => 'Keterangan maksimal 1000 karakter.',
        ];
    }
}
