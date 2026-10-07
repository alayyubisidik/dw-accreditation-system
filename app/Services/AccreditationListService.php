<?php

namespace App\Services;

use App\Models\Accreditation;
use Illuminate\Http\Request;

class AccreditationListService
{
    public function __construct(
        private readonly AccreditationAccessService $access
    ) {
    }

    public function data(Request $request): array
    {
        $user = user();

        $query = $this->access
            ->scopedQuery($user)
            ->with([
                'studyProgram',
                'accreditationIndicators.indicatorTemplate',
            ]);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhereHas('studyProgram', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('study_program_id')) {
            $query->where('study_program_id', $request->study_program_id);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        $accreditations = $query
            ->latest()
            ->get();

        $accreditations->each(function ($accreditation) {
            $indicators = $accreditation->accreditationIndicators;

            $totalIndicators = $indicators->count();

            $completedIndicators = $indicators
                ->filter(fn($indicator) => $indicator->result !== null)
                ->count();

            $achievedIndicators = $indicators
                ->filter(fn($indicator) => $indicator->result !== null && $indicator->result >= $indicator->target)
                ->count();

            $accreditation->totalIndicators = $totalIndicators;
            $accreditation->completedIndicators = $completedIndicators;
            $accreditation->uncompletedIndicators = $totalIndicators - $completedIndicators;
            $accreditation->achievedIndicators = $achievedIndicators;
            $accreditation->unachievedIndicators = max($completedIndicators - $achievedIndicators, 0);
            $accreditation->progress = $totalIndicators > 0
                ? round(($achievedIndicators / $totalIndicators) * 100)
                : 0;
            $accreditation->completion_status = $completedIndicators === $totalIndicators && $totalIndicators > 0
                ? 'completed'
                : 'unfinished';
        });

        if ($request->filled('status')) {
            $accreditations = $accreditations
                ->filter(fn($accreditation) => $accreditation->completion_status === $request->status);
        }

        $studyPrograms = $this->access
            ->scopedStudyPrograms($user)
            ->orderBy('name')
            ->get();

        $years = $this->access
            ->scopedQuery($user)
            ->select('year')
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        return compact('accreditations', 'studyPrograms', 'years');
    }
}
