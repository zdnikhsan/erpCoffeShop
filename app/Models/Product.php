<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'sku',
        'price',
        'category',
        'is_active',
        'image',
    ];

    protected $appends = [
        'image_url',
    ];

    protected function casts(): array
    {
        return [
            'price'     => 'decimal:2',
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get visual photo based on uploaded image or SKU fallback.
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image) {
            if (filter_var($this->image, FILTER_VALIDATE_URL)) {
                return $this->image;
            }
            return asset('storage/' . $this->image);
        }

        $urls = [
            'PRD-ICE-LAT'     => 'https://images.unsplash.com/photo-1517701604599-bb29b565090c?w=500&auto=format&fit=crop&q=60',
            'PRD-KOPI-AREN'   => 'https://images.unsplash.com/photo-1461023058943-07fcbe16d735?w=500&auto=format&fit=crop&q=60',
            'PRD-CAPUCCNO'    => 'https://images.unsplash.com/photo-1534778101976-62847782c213?w=500&auto=format&fit=crop&q=60',
            'PRD-HOT-CHOC'    => 'https://images.unsplash.com/photo-1544787219-7f47ccb76574?w=500&auto=format&fit=crop&q=60',
            'PRD-MATCHA-L'    => 'https://images.unsplash.com/photo-1536256263959-770b48d82b0a?w=500&auto=format&fit=crop&q=60',
            'PRD-OAT-LAT'     => 'https://images.unsplash.com/photo-1576092768241-dec231879fc3?w=500&auto=format&fit=crop&q=60',
            'PRD-ICE-CHOC'    => 'https://images.unsplash.com/photo-1541658016709-82535e94bc69?w=500&auto=format&fit=crop&q=60',
            'PRD-ESPRESSO'    => 'https://images.unsplash.com/photo-1510707572719-ddb8eae8b3b5?w=500&auto=format&fit=crop&q=60',
        ];

        return $urls[$this->sku] ?? 'https://images.unsplash.com/photo-1509042239860-f550ce710b93?w=500&auto=format&fit=crop&q=60';
    }

    /**
     * Relasi Many-to-Many ke model Ingredient (Bahan Baku).
     */
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'ingredient_product')
            ->withPivot('quantity');
    }

    /**
     * Relasi Many-to-Many ke model Order.
     */
    public function orders(): BelongsToMany
    {
        return $this->belongsToMany(Order::class, 'order_product')
            ->withPivot('quantity', 'price');
    }
}
