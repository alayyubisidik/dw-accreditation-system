<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudyProgramAccreditationAgency extends Model
{
    protected $fillable = [
        'name',
        'description',
    ];

    public function studyProgramIndicatorTemplates(): HasMany
    {
        return $this->hasMany(StudyProgramIndicatorTemplate::class);
    }

    public function studyProgramAccreditationBatches(): HasMany
    {
        return $this->hasMany(StudyProgramAccreditationBatch::class);
    }
}
