<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'payment_terms',
    ];

    protected function casts(): array
    {
        return [
            'payment_terms' => 'integer',
        ];
    }

    /**
     * Relasi One-to-Many ke model PurchaseOrder.
     */
    public function purchaseOrders(): \Illuminate\Database\Eloquent\Relations\HasMany
    {
        return $this->hasMany(PurchaseOrder::class);
    }
}
