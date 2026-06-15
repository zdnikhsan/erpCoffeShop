<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'stock',
        'unit',
        'safety_stock',
    ];

    protected function casts(): array
    {
        return [
            'stock'        => 'decimal:2',
            'safety_stock' => 'decimal:2',
        ];
    }

    /**
     * Cek apakah stok di bawah batas minimum.
     */
    public function isLowStock(): bool
    {
        return $this->stock <= $this->safety_stock;
    }

    /**
     * Relasi Many-to-Many ke model Product.
     */
    public function products(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'ingredient_product')
            ->withPivot('quantity');
    }

    /**
     * Relasi Many-to-Many ke model PurchaseOrder.
     */
    public function purchaseOrders(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(PurchaseOrder::class, 'ingredient_purchase_order')
            ->withPivot('quantity', 'quantity_received', 'unit_price')
            ->withTimestamps();
    }
}
