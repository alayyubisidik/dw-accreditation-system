<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UniversityIndicatorTemplate extends Model
{
    protected $fillable = [
        'university_accreditation_agency_id',
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
        return $this->belongsTo(UniversityAccreditationAgency::class, 'university_accreditation_agency_id');
    }

    public function universityAccreditationIndicators(): HasMany
    {
        return $this->hasMany(UniversityAccreditationIndicator::class);
    }
}
