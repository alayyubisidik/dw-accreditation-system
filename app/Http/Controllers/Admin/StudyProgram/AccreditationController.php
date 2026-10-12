<?php

namespace App\Http\Controllers\Admin\StudyProgram;

use App\Http\Controllers\Controller;
use App\Models\StudyProgram;
use App\Models\StudyProgramAccreditation;
use App\Models\StudyProgramAccreditationBatch;
use App\Services\AlertService;
use Illuminate\Http\Request;

class AccreditationController extends Controller
{
    public function index(Request $request)
    {
        $query = StudyProgramAccreditation::query()
            ->with([
                'studyProgram',
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

        if ($request->filled('study_program_id')) {
            $query->where('study_program_id', $request->study_program_id);
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

        $accreditations = $query->latest('id')->get()->map(function (StudyProgramAccreditation $accreditation) {

            $totalIndicators = $accreditation->indicators->count();

            $completedIndicators = $accreditation->indicators
                ->filter(fn ($indicator) => ! is_null($indicator->result))
                ->count();

            $achievedIndicators = $accreditation->indicators
                ->filter(
                    fn ($indicator) => ! is_null($indicator->result) &&
                        ! is_null($indicator->target_absolute) &&
                        $indicator->result >= $indicator->target_absolute
                )
                ->count();

            $accreditation->setAttribute('total_indicators', $totalIndicators);
            $accreditation->setAttribute('completed_indicators', $completedIndicators);
            $accreditation->setAttribute('achieved_indicators', $achievedIndicators);

            return $accreditation;
        });

        $years = StudyProgramAccreditationBatch::query()
            ->distinct()
            ->orderByDesc('year')
            ->pluck('year');

        $studyPrograms = StudyProgram::orderBy('name')->get();

        return view(
            'study-program.accreditation.index',
            compact('accreditations', 'years', 'studyPrograms')
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
            'study-program.accreditation.preview',
            compact('accreditation')
        );
    }

    public function destroy(StudyProgramAccreditation $accreditation)
    {
        $accreditation->delete();

        AlertService::deleted('Akreditasi program studi berhasil dihapus.');

        return redirect()->route('dashboard-admin.study-program.accreditations.index');
    }

    public function toggleComplete(StudyProgramAccreditation $accreditation)
    {
        $newStatus = $accreditation->status === 'completed' ? 'in_progress' : 'completed';
        $accreditation->update(['status' => $newStatus]);

        $label = $newStatus === 'completed' ? 'ditandai selesai' : 'dikembalikan ke in-progress';
        AlertService::updated("Akreditasi berhasil {$label}.");

        return redirect()->back();
    }

    public function showCompare()
    {
        $studyPrograms = StudyProgram::whereHas('studyProgramAccreditations')
            ->orderBy('name')
            ->get();

        $batches = StudyProgramAccreditationBatch::with('agency')
            ->whereHas('studyProgramAccreditations')
            ->orderByDesc('year')
            ->get();

        return view('study-program.accreditation.compare-select', compact('studyPrograms', 'batches'));
    }

    public function compare(Request $request)
    {
        $request->validate([
            'study_program_id' => 'required|exists:study_programs,id',
            'batch_a' => 'required|exists:study_program_accreditation_batches,id|different:batch_b',
            'batch_b' => 'required|exists:study_program_accreditation_batches,id',
        ], [
            'study_program_id.required' => 'Pilih program studi.',
            'batch_a.required' => 'Pilih Batch A.',
            'batch_b.required' => 'Pilih Batch B.',
            'batch_a.different' => 'Pilih dua batch yang berbeda.',
        ]);

        $studyProgram = StudyProgram::findOrFail($request->study_program_id);

        $batchA = StudyProgramAccreditationBatch::with([
            'agency',
            'studyProgramAccreditations' => fn ($query) => $query
                ->where('study_program_id', $studyProgram->id)
                ->with('indicators'),
        ])->findOrFail($request->batch_a);

        $batchB = StudyProgramAccreditationBatch::with([
            'agency',
            'studyProgramAccreditations' => fn ($query) => $query
                ->where('study_program_id', $studyProgram->id)
                ->with('indicators'),
        ])->findOrFail($request->batch_b);

        $accA = $batchA->studyProgramAccreditations->first();
        $accB = $batchB->studyProgramAccreditations->first();

        abort_if(! $accA || ! $accB, 404, 'Salah satu batch belum memiliki data akreditasi untuk program studi ini.');

        $indA = $accA->indicators->keyBy('study_program_indicator_template_id');
        $indB = $accB->indicators->keyBy('study_program_indicator_template_id');

        $allTemplateIds = $indA->keys()->merge($indB->keys())->unique()->filter();

        $onlyInA = $accA->indicators->whereNull('study_program_indicator_template_id');
        $onlyInB = $accB->indicators->whereNull('study_program_indicator_template_id');

        $rows = $allTemplateIds->map(function ($templateId) use ($indA, $indB) {
            $a = $indA->get($templateId);
            $b = $indB->get($templateId);

            $resultA = $a?->result;
            $resultB = $b?->result;

            $trend = null;
            if ($resultA !== null && $resultB !== null) {
                if ($resultB > $resultA) {
                    $trend = 'naik';
                } elseif ($resultB < $resultA) {
                    $trend = 'turun';
                } else {
                    $trend = 'sama';
                }
            }

            return [
                'title' => $a?->title ?? $b?->title,
                'formula' => $a?->formula_type ?? $b?->formula_type,
                'target_a' => $a?->target,
                'target_b' => $b?->target,
                'result_a' => $resultA,
                'result_b' => $resultB,
                'gap_a' => $a?->gap,
                'gap_b' => $b?->gap,
                'gap_unit' => $a?->gap_unit ?? $b?->gap_unit,
                'achieved_a' => $resultA !== null && $a?->target !== null && $resultA >= $a->target,
                'achieved_b' => $resultB !== null && $b?->target !== null && $resultB >= $b->target,
                'trend' => $trend,
                'only_in_a' => ! $b,
                'only_in_b' => ! $a,
            ];
        })->values();

        $summaryA = [
            'total' => $accA->indicators->count(),
            'filled' => $accA->indicators->filter(fn ($i) => $i->result !== null)->count(),
            'achieved' => $accA->indicators->filter(fn ($i) => $i->result !== null && $i->result >= $i->target)->count(),
        ];
        $summaryB = [
            'total' => $accB->indicators->count(),
            'filled' => $accB->indicators->filter(fn ($i) => $i->result !== null)->count(),
            'achieved' => $accB->indicators->filter(fn ($i) => $i->result !== null && $i->result >= $i->target)->count(),
        ];

        return view('study-program.accreditation.compare', compact(
            'studyProgram', 'batchA', 'batchB',
            'accA', 'accB',
            'rows',
            'summaryA', 'summaryB',
            'onlyInA', 'onlyInB',
        ));
    }
}
