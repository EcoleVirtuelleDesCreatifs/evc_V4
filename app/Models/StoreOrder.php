<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use App\Models\User;

class StoreOrder extends Model
{
    protected $fillable = [
        'user_id',
        'order_number',
        'nom',
        'prenoms',
        'numero',
        'lieu',
        'delivery_mode',
        'payment_method',
        'autre',
        'items',
        'subtotal',
        'delivery_cost',
        'discount',
        'promo_code',
        'final_total',
        'total',
        'status',
    ];

    protected $casts = [
        'items' => 'array',
        'user_id' => 'integer',
        'subtotal' => 'integer',
        'delivery_cost' => 'integer',
        'discount' => 'integer',
        'final_total' => 'integer',
        'total' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
