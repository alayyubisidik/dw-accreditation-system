<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class StudyProgram extends Model
{
    protected $fillable = [
        'faculty_id',
        'name',
    ];

    public function faculty(): BelongsTo
    {
        return $this->belongsTo(Faculty::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function studyProgramAccreditations(): HasMany
    {
        return $this->hasMany(StudyProgramAccreditation::class);
    }

    public function indicatorTemplates(): HasMany
    {
        return $this->hasMany(StudyProgramIndicatorTemplate::class);
    }
}

