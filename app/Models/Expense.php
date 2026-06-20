<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Expense extends Model
{
    use HasFactory;

    protected $fillable = [
        'expense_number',
        'category',
        'amount',
        'note',
        'date',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'date'   => 'date',
        ];
    }

    /**
     * Kategori pengeluaran yang tersedia.
     */
    public const CATEGORIES = [
        'Gaji',
        'Listrik & Air',
        'Sewa Tempat',
        'Maintenance',
        'Lainnya',
    ];

    /**
     * Auto-generate expense_number on creation.
     * Format: EXP-YYYYMM-0001
     */
    protected static function booted()
    {
        static::creating(function (Expense $expense) {
            $expenseDate = $expense->date ? Carbon::parse($expense->date) : Carbon::now();
            $prefix = 'EXP-' . $expenseDate->format('Ym') . '-';

            // Find the last expense number with this prefix
            $lastExpense = self::where('expense_number', 'like', $prefix . '%')
                ->orderBy('expense_number', 'desc')
                ->first();

            $nextSequence = 1;
            if ($lastExpense) {
                $lastSequence = (int) substr($lastExpense->expense_number, -4);
                $nextSequence = $lastSequence + 1;
            }

            $expense->expense_number = $prefix . sprintf('%04d', $nextSequence);
        });
    }
}
