<?php

namespace App\Services;

use App\Models\Accreditation;
use App\Models\StudyProgram;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;

class AccreditationAccessService
{
    public function scopedQuery(User $user): Builder
    {
        return Accreditation::query()
            ->when($user->role === 'reviewer', function (Builder $query) use ($user) {
                $query->whereHas('studyProgram', function (Builder $query) use ($user) {
                    $query->where('faculty_id', $user->faculty_id);
                });
            })
            ->when($user->role === 'operator', function (Builder $query) use ($user) {
                $query->where('study_program_id', $user->study_program_id);
            });
    }

    public function scopedStudyPrograms(User $user): Builder
    {
        return StudyProgram::query()
            ->when($user->role === 'reviewer', function (Builder $query) use ($user) {
                $query->where('faculty_id', $user->faculty_id);
            })
            ->when($user->role === 'operator', function (Builder $query) use ($user) {
                $query->where('id', $user->study_program_id);
            });
    }

    public function canView(User $user, Accreditation $accreditation): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        if ($user->role === 'reviewer') {
            return $accreditation->studyProgram?->faculty_id === $user->faculty_id;
        }

        if ($user->role === 'operator') {
            return $accreditation->study_program_id === $user->study_program_id;
        }

        return false;
    }

    public function canManage(User $user, Accreditation $accreditation): bool
    {
        if ($user->role === 'admin') {
            return true;
        }

        return $user->role === 'operator'
            && $accreditation->study_program_id === $user->study_program_id;
    }

    public function authorizeView(User $user, Accreditation $accreditation): void
    {
        abort_unless($this->canView($user, $accreditation), 403, 'Anda tidak memiliki akses.');
    }

    public function authorizeManage(User $user, Accreditation $accreditation): void
    {
        abort_unless($this->canManage($user, $accreditation), 403, 'Anda tidak memiliki akses.');
    }
}
