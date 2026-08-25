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
        'autre',
        'items',
        'total',
        'status',
    ];

    protected $casts = [
        'items' => 'array',
        'user_id' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
