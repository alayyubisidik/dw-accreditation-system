<?php

namespace App\Http\Controllers\Admin\University;

use App\Http\Controllers\Controller;
use App\Models\UniversityAccreditation;
use App\Models\UniversityAccreditationIndicator;
use App\Services\AlertService;
use Illuminate\Http\Request;

class AccreditationInputController extends Controller
{
    public function index(Request $request, UniversityAccreditation $accreditation)
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

        return view('university.accreditation-input.index', compact(
            'accreditation',
            'indicators',
            'totalIndicators',
            'completedIndicators',
            'completedTarget',
            'unfinishedTarget',
            'progress'
        ));
    }

    public function edit(UniversityAccreditation $accreditation, UniversityAccreditationIndicator $indicator)
    {
        abort_if($indicator->university_accreditation_id !== $accreditation->id, 404);

        $accreditation->load('batch.agency');

        return view('university.accreditation-input.edit', compact('accreditation', 'indicator'));
    }

    public function update(Request $request, UniversityAccreditation $accreditation, UniversityAccreditationIndicator $indicator)
    {
        abort_if($indicator->university_accreditation_id !== $accreditation->id, 404);

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

        // Auto-complete: jika semua indikator sudah terisi (result tidak null),
        // otomatis ubah status akreditasi menjadi 'completed'
        $totalIndicators  = $accreditation->indicators()->count();
        $filledIndicators = $accreditation->indicators()->whereNotNull('result')->count();

        if ($totalIndicators > 0 && $filledIndicators === $totalIndicators && $accreditation->status !== 'completed') {
            $accreditation->update(['status' => 'completed']);
            AlertService::updated('Data indikator berhasil disimpan. Semua indikator telah terisi — akreditasi otomatis ditandai Selesai.');
        } else {
            AlertService::updated('Data indikator berhasil disimpan.');
        }

        return redirect()->route('dashboard-admin.university.accreditations.input.index', $accreditation);
    }
}
