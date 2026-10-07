<?php

namespace App\Http\Controllers\Operator;

use App\Http\Controllers\Controller;
use App\Models\StudyProgramAccreditation;
use App\Models\StudyProgramAccreditationBatch;
use Illuminate\Http\Request;

class AccreditationController extends Controller
{
    /**
     * Menampilkan daftar Akreditasi milik Program Studi operator yang login.
     * Data akreditasi sendiri dibuat otomatis saat admin membuat Batch Akreditasi,
     * sehingga di sini operator hanya menampilkan, memfilter, dan memantau progress.
     */
    public function index(Request $request)
    {
        $user = user();

        $query = StudyProgramAccreditation::query()
            ->with([
                'studyProgram',
                'batch.agency',
                'indicators'
            ])
            ->where('study_program_id', $user->study_program_id);

        // FILTER: pencarian nama batch / nama lembaga akreditasi
        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('batch', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('agency', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        // FILTER: tahun
        if ($request->filled('year')) {
            $query->whereHas('batch', function ($q) use ($request) {
                $q->where('year', $request->year);
            });
        }

        // FILTER: status
        if ($request->filled('status')) {
            if ($request->status === 'completed') {
                $query->where('status', 'completed');
            } elseif ($request->status === 'unfinished') {
                $query->where('status', 'in_progress');
            }
        }

        $accreditations = $query
            ->latest('id')
            ->get()
            ->map(function (StudyProgramAccreditation $accreditation) {

                $totalIndicators = $accreditation->indicators->count();

                $completedIndicators = $accreditation->indicators
                    ->filter(fn($indicator) => !is_null($indicator->result))
                    ->count();

                $achievedIndicators = $accreditation->indicators
                    ->filter(function ($indicator) {
                        return !is_null($indicator->result)
                            && !is_null($indicator->target_absolute)
                            && $indicator->result >= $indicator->target_absolute;
                    })
                    ->count();

                $accreditation->setAttribute('total_indicators', $totalIndicators);
                $accreditation->setAttribute('completed_indicators', $completedIndicators);
                $accreditation->setAttribute('achieved_indicators', $achievedIndicators);

                return $accreditation;
            });

        // Semua tahun yang tersedia
        $years = StudyProgramAccreditationBatch::query()
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        return view(
            'operator.accreditation.index',
            compact(
                'accreditations',
                'years'
            )
        );
    }


    public function preview(StudyProgramAccreditation $accreditation)
    {
        $accreditation->load([
            'studyProgram',
            'batch.agency',
            'indicators',
        ]);

        return view(
            'operator.accreditation.preview',
            compact('accreditation')
        );
    }
}
