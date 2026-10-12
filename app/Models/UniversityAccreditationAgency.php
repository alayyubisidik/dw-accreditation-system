<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class UniversityAccreditationAgency extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function universityIndicatorTemplates(): HasMany
    {
        return $this->hasMany(UniversityIndicatorTemplate::class);
    }

    public function universityAccreditations(): HasMany
    {
        return $this->hasMany(UniversityAccreditation::class);
    }

    public function accreditations(): HasMany
    {
        return $this->hasMany(UniversityAccreditation::class);
    }
}
