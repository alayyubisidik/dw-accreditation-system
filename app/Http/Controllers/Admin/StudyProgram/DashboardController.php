<?php

namespace App\Http\Controllers\Admin\StudyProgram;

use App\Http\Controllers\Controller;
use App\Models\StudyProgram;
use App\Models\StudyProgramAccreditation;
use App\Models\StudyProgramAccreditationAgency;
use App\Models\StudyProgramAccreditationBatch;
use App\Models\StudyProgramAccreditationIndicator;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Ringkasan Umum ──────────────────────────────────────────────
        $totalStudyPrograms   = StudyProgram::count();
        $totalAgencies        = StudyProgramAccreditationAgency::count();
        $totalBatches         = StudyProgramAccreditationBatch::count();
        $totalAccreditations  = StudyProgramAccreditation::count();

        // ── Batch Aktif ─────────────────────────────────────────────────
        $activeBatch = StudyProgramAccreditationBatch::where('is_active', true)
            ->with('agency')
            ->latest()
            ->first();

        // ── Progress Akreditasi (semua batch) ──────────────────────────
        $allBatchesProgress = StudyProgramAccreditationBatch::with(['agency', 'studyProgramAccreditations.studyProgram', 'studyProgramAccreditations.indicators'])
            ->latest()
            ->get()
            ->map(function (StudyProgramAccreditationBatch $batch) {
                $progress = $batch->studyProgramAccreditations->map(function (StudyProgramAccreditation $acc) {
                    $total    = $acc->indicators->count();
                    $filled   = $acc->indicators->filter(fn($i) => !is_null($i->result))->count();
                    $achieved = $acc->indicators->filter(
                        fn($i) => !is_null($i->result) && !is_null($i->target_absolute) && $i->result >= $i->target_absolute
                    )->count();
                    $pct      = $total > 0 ? round(($filled / $total) * 100) : 0;

                    $acc->setAttribute('total_indicators', $total);
                    $acc->setAttribute('filled_indicators', $filled);
                    $acc->setAttribute('achieved_indicators', $achieved);
                    $acc->setAttribute('progress', $pct);
                    return $acc;
                })->sortByDesc('progress');

                $batch->setRelation('accreditationProgress', $progress);
                return $batch;
            });

        // ── Progress Akreditasi (batch aktif saja, untuk kompatibilitas) ─
        $accreditationProgress = $allBatchesProgress
            ->first(fn($b) => $b->is_active)
            ?->accreditationProgress ?? collect();

        // ── Statistik Indikator Global ──────────────────────────────────
        $allIndicators      = StudyProgramAccreditationIndicator::count();
        $filledIndicators   = StudyProgramAccreditationIndicator::whereNotNull('result')->count();
        $achievedIndicators = StudyProgramAccreditationIndicator::whereNotNull('result')
            ->whereNotNull('target_absolute')
            ->whereColumn('result', '>=', 'target_absolute')
            ->count();

        $globalFillRate    = $allIndicators > 0 ? round(($filledIndicators / $allIndicators) * 100) : 0;
        $globalAchieveRate = $filledIndicators > 0 ? round(($achievedIndicators / $filledIndicators) * 100) : 0;

        // ── Status Akreditasi ────────────────────────────────────────────
        $completedCount   = StudyProgramAccreditation::where('status', 'completed')->count();
        $inProgressCount  = StudyProgramAccreditation::where('status', 'in_progress')->count();

        return view('study-program.dashboard.index', compact(
            'totalStudyPrograms',
            'totalAgencies',
            'totalBatches',
            'totalAccreditations',
            'activeBatch',
            'accreditationProgress',
            'allBatchesProgress',
            'allIndicators',
            'filledIndicators',
            'achievedIndicators',
            'globalFillRate',
            'globalAchieveRate',
            'completedCount',
            'inProgressCount',
        ));
    }
}
