<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TpSubmissionFile extends Model
{
    protected $table = 'tp_submission_files';

    protected $fillable = [
        'tp_assignment_id',
        'file_name',
        'file_path',
        'file_size',
        'mime_type',
    ];

    /**
     * Relation avec TpAssignment
     */
    public function tpAssignment()
    {
        return $this->belongsTo(TpAssignment::class, 'tp_assignment_id');
    }
}
