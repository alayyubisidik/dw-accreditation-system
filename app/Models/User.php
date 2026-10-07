<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $fillable = [
        'study_program_id',
        'name',
        'email',
        'password',
        'role',
        'avatar',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'password'  => 'hashed',
            'is_active' => 'boolean',
        ];
    }

    public function studyProgram(): BelongsTo
    {
        return $this->belongsTo(StudyProgram::class);
    }

    public function faculty(): \Illuminate\Database\Eloquent\Relations\HasOneThrough
    {
        return $this->hasOneThrough(Faculty::class, StudyProgram::class, 'id', 'id', 'study_program_id', 'faculty_id');
    }

    public function uploadedUniversityIndicatorFiles(): HasMany
    {
        return $this->hasMany(UniversityIndicatorFile::class, 'uploaded_by');
    }

    public function uploadedStudyProgramIndicatorFiles(): HasMany
    {
        return $this->hasMany(StudyProgramIndicatorFile::class, 'uploaded_by');
    }
}
