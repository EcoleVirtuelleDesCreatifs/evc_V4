<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StoreOrder extends Model
{
    protected $fillable = [
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
    ];
}
