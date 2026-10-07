<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudyProgramAccreditation extends Model
{
    protected $fillable = [
        'study_program_accreditation_batch_id',
        'study_program_id',
        'status',
    ];

    public function batch(): BelongsTo
    {
        return $this->belongsTo(StudyProgramAccreditationBatch::class, 'study_program_accreditation_batch_id');
    }

    public function studyProgram(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function indicators(): HasMany
    {
        return $this->hasMany(StudyProgramAccreditationIndicator::class);
    }
}
