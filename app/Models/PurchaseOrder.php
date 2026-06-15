<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PurchaseOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'po_number',
        'supplier_id',
        'status',
        'total_amount',
        'order_date',
        'received_date',
    ];

    protected function casts(): array
    {
        return [
            'order_date'    => 'date',
            'received_date' => 'date',
            'total_amount'  => 'decimal:2',
        ];
    }

    /**
     * Auto-generate po_number on creation.
     */
    protected static function booted()
    {
        static::creating(function (PurchaseOrder $purchaseOrder) {
            $orderDate = $purchaseOrder->order_date ? Carbon::parse($purchaseOrder->order_date) : Carbon::now();
            $prefix = 'PO-' . $orderDate->format('Ym') . '-';

            // Find the last PO number with this prefix
            $lastPo = self::where('po_number', 'like', $prefix . '%')
                ->orderBy('po_number', 'desc')
                ->first();

            $nextSequence = 1;
            if ($lastPo) {
                // Extract last 4 digits
                $lastSequence = (int) substr($lastPo->po_number, -4);
                $nextSequence = $lastSequence + 1;
            }

            $purchaseOrder->po_number = $prefix . sprintf('%04d', $nextSequence);
        });
    }

    /**
     * Relasi BelongsTo ke Supplier.
     */
    public function supplier(): BelongsTo
    {
        return $this->belongsTo(Supplier::class);
    }

    /**
     * Relasi Many-to-Many ke Ingredient.
     */
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'ingredient_purchase_order')
            ->withPivot('quantity', 'quantity_received', 'unit_price')
            ->withTimestamps();
    }
}
