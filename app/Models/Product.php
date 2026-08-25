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
        'is_active',
    ];

    protected $casts = [
        'price' => 'integer',
        'delivery_cost' => 'integer',
        'view_count' => 'integer',
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
}
