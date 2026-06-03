<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JuryEvaluationScore extends Model
{
    use HasFactory;

    protected $fillable = [
        'jury_evaluation_id',
        'category_key',
        'category_label',
        'criterion_key',
        'criterion_label',
        'score',
        'max_score',
    ];

    public function evaluation()
    {
        return $this->belongsTo(JuryEvaluation::class, 'jury_evaluation_id');
    }
}
