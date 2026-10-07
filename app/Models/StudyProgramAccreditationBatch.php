<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudyProgramAccreditationBatch extends Model
{
    protected $fillable = [
        'study_program_accreditation_agency_id',
        'name',
        'year',
        'is_active',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(StudyProgramAccreditationAgency::class, 'study_program_accreditation_agency_id');
    }

    public function studyProgramAccreditations(): HasMany
    {
        return $this->hasMany(StudyProgramAccreditation::class);
    }
}
