<?php

namespace App\Http\Controllers\Admin\University;

use App\Http\Controllers\Controller;
use App\Models\UniversityAccreditation;
use App\Models\UniversityAccreditationBatch;
use App\Services\AlertService;
use Illuminate\Http\Request;

class AccreditationController extends Controller
{
    public function index(Request $request)
    {
        $query = UniversityAccreditation::query()
            ->with([
                'batch.agency',
                'indicators',
            ]);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->whereHas('batch', function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('agency', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        if ($request->filled('year')) {
            $query->whereHas('batch', function ($q) use ($request) {
                $q->where('year', $request->year);
            });
        }

        if ($request->filled('status')) {
            if ($request->status === 'completed') {
                $query->where('status', 'completed');
            } elseif ($request->status === 'unfinished') {
                $query->where('status', 'in_progress');
            }
        }

        $accreditations = $query->latest('id')->get()->map(function (UniversityAccreditation $accreditation) {

            $totalIndicators = $accreditation->indicators->count();

            $completedIndicators = $accreditation->indicators
                ->filter(fn($indicator) => !is_null($indicator->result))
                ->count();

            $achievedIndicators = $accreditation->indicators
                ->filter(
                    fn($indicator) =>
                    !is_null($indicator->result) &&
                        !is_null($indicator->target_absolute) &&
                        $indicator->result >= $indicator->target_absolute
                )
                ->count();

            $accreditation->setAttribute('total_indicators', $totalIndicators);
            $accreditation->setAttribute('completed_indicators', $completedIndicators);
            $accreditation->setAttribute('achieved_indicators', $achievedIndicators);

            return $accreditation;
        });

        $years = UniversityAccreditationBatch::query()
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        return view(
            'university.accreditation.index',
            compact('accreditations', 'years')
        );
    }


    public function preview(UniversityAccreditation $accreditation)
    {
        $accreditation->load([
            'batch.agency',
            'indicators',
        ]);

        return view(
            'university.accreditation.preview',
            compact('accreditation')
        );
    }

    public function toggleComplete(UniversityAccreditation $accreditation)
    {
        $newStatus = $accreditation->status === 'completed' ? 'in_progress' : 'completed';
        $accreditation->update(['status' => $newStatus]);

        $label = $newStatus === 'completed' ? 'ditandai selesai' : 'dikembalikan ke in-progress';
        AlertService::updated("Akreditasi berhasil {$label}.");

        return redirect()->back();
    }
}
