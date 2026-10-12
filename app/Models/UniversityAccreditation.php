<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UniversityAccreditation extends Model
{
    protected $fillable = [
        'university_accreditation_agency_id',
        'name',
        'year',
        'status',
        'description',
    ];

    protected function casts(): array
    {
        return [
            'year' => 'integer',
        ];
    }

    public function agency(): BelongsTo
    {
        return $this->belongsTo(UniversityAccreditationAgency::class, 'university_accreditation_agency_id');
    }

    public function indicators(): HasMany
    {
        return $this->hasMany(UniversityAccreditationIndicator::class);
    }
}
