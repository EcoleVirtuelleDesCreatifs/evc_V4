<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'slug',
        'summary',
        'description',
        'price',
        'delivery_mode',
        'delivery_cost',
        'email',
        'seo_geo',
        'image',
        'images',
        'view_count',
        'stock',
        'old_price',
        'is_active',
    ];

    protected $casts = [
        'price' => 'integer',
        'delivery_cost' => 'integer',
        'view_count' => 'integer',
        'stock' => 'integer',
        'old_price' => 'integer',
        'is_active' => 'boolean',
        'images' => 'array',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 0, ',', ' ') . ' FCFA';
    }

    public function getIsPromotionAttribute(): bool
    {
        return $this->old_price !== null && $this->old_price > $this->price;
    }

    public function getFormattedOldPriceAttribute(): ?string
    {
        return $this->old_price ? number_format($this->old_price, 0, ',', ' ') . ' FCFA' : null;
    }

    public function getStockStatusAttribute(): string
    {
        if ($this->stock <= 0) return 'rupture';
        if ($this->stock <= 10) return 'stock_limite';
        return 'en_stock';
    }
}
