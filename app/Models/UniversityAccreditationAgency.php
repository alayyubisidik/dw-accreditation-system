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

    public function universityAccreditationBatches(): HasMany
    {
        return $this->hasMany(UniversityAccreditationBatch::class);
    }
}
