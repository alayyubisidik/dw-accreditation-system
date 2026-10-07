<?php

namespace App\Http\Controllers\Admin\StudyProgram;

use App\Http\Controllers\Controller;
use App\Models\StudyProgramAccreditation;
use App\Models\StudyProgramAccreditationIndicator;
use App\Services\AlertService;
use Illuminate\Http\Request;

class AccreditationInputController extends Controller
{
    public function index(Request $request, StudyProgramAccreditation $accreditation)
    {
        $accreditation->load('batch.agency');

        $query = $accreditation->indicators();

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('filter')) {

            switch ($request->filter) {

                case 'incomplete':
                    $query->whereNull('result');
                    break;

                case 'unfinished':
                    $query->whereNotNull('result')
                        ->whereColumn('result', '<', 'target');
                    break;

                case 'achieved':
                    $query->whereNotNull('result')
                        ->whereColumn('result', '>=', 'target');
                    break;

                case 'gap':
                    $query
                        ->orderByRaw("FIELD(formula_type, 'percentage', 'ratio')")
                        ->orderByRaw("
                        CASE
                            WHEN result IS NULL THEN 2
                            WHEN result >= target THEN 1
                            ELSE 0
                        END
                    ")
                        ->orderByDesc('gap');
                    break;
            }
        }

        if ($request->filter !== 'gap') {
            $query->orderBy('title');
        }

        $indicators = $query->get();

        $baseQuery = $accreditation->indicators();

        $totalIndicators = (clone $baseQuery)->count();

        $completedIndicators = (clone $baseQuery)
            ->whereNotNull('result')
            ->count();

        $completedTarget = (clone $baseQuery)
            ->whereNotNull('result')
            ->whereColumn('result', '>=', 'target')
            ->count();

        $unfinishedTarget = (clone $baseQuery)
            ->whereNotNull('result')
            ->whereColumn('result', '<', 'target')
            ->count();

        $progress = $totalIndicators > 0
            ? round(($completedIndicators / $totalIndicators) * 100)
            : 0;

        return view('study-program.accreditation-input.index', compact(
            'accreditation',
            'indicators',
            'totalIndicators',
            'completedIndicators',
            'completedTarget',
            'unfinishedTarget',
            'progress'
        ));
    }

    public function edit(StudyProgramAccreditation $accreditation, StudyProgramAccreditationIndicator $indicator)
    {
        abort_if($indicator->study_program_accreditation_id !== $accreditation->id, 404);

        $accreditation->load('batch.agency');

        return view('study-program.accreditation-input.edit', compact('accreditation', 'indicator'));
    }

    public function update(Request $request, StudyProgramAccreditation $accreditation, StudyProgramAccreditationIndicator $indicator)
    {
        abort_if($indicator->study_program_accreditation_id !== $accreditation->id, 404);

        $validated = $request->validate([
            'value_1' => 'required|numeric|min:0',
            'value_2' => 'required|numeric|min:0.01',
        ], [
            'value_1.required' => 'Nilai ' . $indicator->label_1 . ' wajib diisi.',
            'value_1.numeric'  => 'Nilai ' . $indicator->label_1 . ' harus berupa angka.',
            'value_1.min'      => 'Nilai ' . $indicator->label_1 . ' tidak boleh negatif.',
            'value_2.required' => 'Nilai ' . ($indicator->label_2 ?? 'penyebut') . ' wajib diisi.',
            'value_2.numeric'  => 'Nilai ' . ($indicator->label_2 ?? 'penyebut') . ' harus berupa angka.',
            'value_2.min'      => 'Nilai ' . ($indicator->label_2 ?? 'penyebut') . ' harus lebih besar dari 0.',
        ]);

        $value1 = $validated['value_1'];
        $value2 = $validated['value_2'];

        if ($indicator->formula_type === 'percentage') {
            $result         = ($value1 / $value2) * 100;
            $targetAbsolute = ($indicator->target / 100) * $value2;
        } else {
            $result         = $value1 / $value2;
            $targetAbsolute = $indicator->target * $value2;
        }

        $gap = max($targetAbsolute - $value1, 0);

        $indicator->update([
            'value_1'         => $value1,
            'value_2'         => $value2,
            'result'          => round($result, 2),
            'target_absolute' => round($targetAbsolute, 2),
            'gap'             => round($gap, 2),
        ]);

        AlertService::updated('Data indikator berhasil disimpan.');

        // ── Auto: perbarui status akreditasi ──────────────────────────
        $accreditation->refresh();
        $total  = $accreditation->indicators()->count();
        $filled = $accreditation->indicators()->whereNotNull('result')->count();

        $newStatus = ($total > 0 && $filled === $total) ? 'completed' : 'in_progress';
        if ($accreditation->status !== $newStatus) {
            $accreditation->update(['status' => $newStatus]);
        }

        return redirect()->route('dashboard-admin.study-program.accreditations.input.index', $accreditation);
    }
}
