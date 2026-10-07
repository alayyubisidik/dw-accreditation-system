<?php

namespace App\Http\Controllers\Admin\University;

use App\Http\Controllers\Controller;
use App\Models\UniversityAccreditation;
use App\Models\UniversityAccreditationAgency;
use App\Models\UniversityAccreditationBatch;
use App\Models\UniversityAccreditationIndicator;

class DashboardController extends Controller
{
    public function index()
    {
        // ── Ringkasan Umum ──────────────────────────────────────────────
        $totalAgencies       = UniversityAccreditationAgency::count();
        $totalBatches        = UniversityAccreditationBatch::count();
        $totalAccreditations = UniversityAccreditation::count();

        // ── Batch Aktif ─────────────────────────────────────────────────
        $activeBatch = UniversityAccreditationBatch::where('is_active', true)
            ->with('agency')
            ->latest()
            ->first();

        // ── Progress Akreditasi (semua batch) ───────────────────────────
        $allBatchesProgress = UniversityAccreditationBatch::with([
            'agency',
            'universityAccreditation.indicators',
        ])
            ->latest()
            ->get()
            ->map(function (UniversityAccreditationBatch $batch) {
                $acc = $batch->universityAccreditation;

                if ($acc) {
                    $total    = $acc->indicators->count();
                    $filled   = $acc->indicators->filter(fn($i) => !is_null($i->result))->count();
                    $achieved = $acc->indicators->filter(
                        fn($i) => !is_null($i->result) && !is_null($i->target_absolute) && $i->result >= $i->target_absolute
                    )->count();
                    $pct = $total > 0 ? round(($filled / $total) * 100) : 0;

                    $acc->setAttribute('total_indicators', $total);
                    $acc->setAttribute('filled_indicators', $filled);
                    $acc->setAttribute('achieved_indicators', $achieved);
                    $acc->setAttribute('progress', $pct);
                }

                $batch->setRelation('accreditationData', $acc);
                return $batch;
            });

        return view('university.dashboard.index', compact(
            'totalAgencies',
            'totalBatches',
            'totalAccreditations',
            'activeBatch',
            'allBatchesProgress',
        ));
    }
}
