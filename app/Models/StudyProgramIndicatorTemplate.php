<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudyProgramIndicatorTemplate extends Model
{
    protected $fillable = [
        'study_program_accreditation_agency_id',
        'study_program_id',
        'title',
        'description',
        'formula_type',
        'label_1',
        'label_2',
        'target',
        'gap_unit',
        'strategic_reason',
    ];

    protected function casts(): array
    {
        return [
            'target' => 'decimal:2',
        ];
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(StudyProgramAccreditationAgency::class, 'study_program_accreditation_agency_id');
    }

    public function studyProgram(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function studyProgramAccreditationIndicators(): HasMany
    {
        return $this->hasMany(StudyProgramAccreditationIndicator::class);
    }

    /**
     * Scope: template umum (study_program_id IS NULL) ATAU khusus prodi $studyProgramId.
     */
    public function scopeForStudyProgram(Builder $query, int $studyProgramId): Builder
    {
        return $query->where(function (Builder $q) use ($studyProgramId) {
            $q->whereNull('study_program_id')
              ->orWhere('study_program_id', $studyProgramId);
        });
    }
}
