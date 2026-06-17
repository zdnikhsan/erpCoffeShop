<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_number',
        'order_type',
        'table_number',
        'payment_method',
        'subtotal',
        'tax',
        'discount',
        'total_pay',
        'cashier_id',
    ];

    protected function casts(): array
    {
        return [
            'subtotal'  => 'decimal:2',
            'tax'       => 'decimal:2',
            'discount'  => 'decimal:2',
            'total_pay' => 'decimal:2',
        ];
    }

    /**
     * Relasi ke User (Kasir).
     */
    public function cashier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'cashier_id');
    }

    /**
     * Relasi ke Product (Many-to-Many).
     */
    public function products(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'order_product')
            ->withPivot('quantity', 'price');
    }
}
