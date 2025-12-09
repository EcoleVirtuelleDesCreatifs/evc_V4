<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountingBudget extends Model
{
    use HasFactory;

    protected $fillable = [
        'category',
        'type',
        'year',
        'amount',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'year' => 'integer',
    ];
}
