<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JuryEvaluation extends Model
{
    use HasFactory;

    protected $fillable = [
        'jury_member_id',
        'jury_name',
        'jury_function',
        'jury_email',
        'evaluation_date',
        'group_name',
        'global_comment',
        'total_score',
        'status',
    ];

    protected $casts = [
        'evaluation_date' => 'date',
    ];

    public function scores()
    {
        return $this->hasMany(JuryEvaluationScore::class);
    }

    public function juryMember()
    {
        return $this->belongsTo(JuryMember::class);
    }
}
