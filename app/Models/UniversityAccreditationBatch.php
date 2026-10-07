<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class UniversityAccreditationBatch extends Model
{
    protected $fillable = [
        'university_accreditation_agency_id',
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
        return $this->belongsTo(UniversityAccreditationAgency::class, 'university_accreditation_agency_id');
    }

    public function universityAccreditation(): HasOne
    {
        return $this->hasOne(UniversityAccreditation::class);
    }
}
