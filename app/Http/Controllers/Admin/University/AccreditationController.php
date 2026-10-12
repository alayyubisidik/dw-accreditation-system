<?php

namespace App\Http\Controllers\Admin\University;

use App\Http\Controllers\Controller;
use App\Models\UniversityAccreditation;
use App\Models\UniversityAccreditationAgency;
use App\Services\AlertService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Schema;

class AccreditationController extends Controller
{
    public function index(Request $request)
    {
        $query = UniversityAccreditation::query()
            ->with([
                'agency',
                'indicators',
            ]);

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhereHas('agency', function ($q2) use ($search) {
                        $q2->where('name', 'like', "%{$search}%");
                    });
            });
        }

        $hasYearColumn = Schema::hasColumn('university_accreditations', 'year');

        if ($hasYearColumn && $request->filled('year')) {
            $query->where('year', $request->year);
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
            $completedIndicators = $accreditation->indicators->filter(fn ($indicator) => ! is_null($indicator->result))->count();
            $achievedIndicators = $accreditation->indicators->filter(
                fn ($indicator) => ! is_null($indicator->result) &&
                    ! is_null($indicator->target_absolute) &&
                    $indicator->result >= $indicator->target_absolute
            )->count();

            $accreditation->setAttribute('total_indicators', $totalIndicators);
            $accreditation->setAttribute('completed_indicators', $completedIndicators);
            $accreditation->setAttribute('achieved_indicators', $achievedIndicators);

            return $accreditation;
        });

        $years = $hasYearColumn
            ? UniversityAccreditation::query()
                ->distinct()
                ->orderByDesc('year')
                ->pluck('year')
            : collect();

        return view('university.accreditation.index', compact('accreditations', 'years'));
    }

    public function create()
    {
        $agencies = UniversityAccreditationAgency::orderBy('name')->get();

        return view('university.accreditation.create', compact('agencies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'university_accreditation_agency_id' => 'required|exists:university_accreditation_agencies,id',
            'name' => 'nullable|string|max:255',
            'year' => 'required|digits:4|integer|min:2000|max:2100',
            'description' => 'nullable|string',
        ], [
            'university_accreditation_agency_id.required' => 'Lembaga akreditasi wajib dipilih.',
            'university_accreditation_agency_id.exists' => 'Lembaga akreditasi tidak valid.',
            'university_accreditation_agency_id.unique' => 'Akreditasi untuk lembaga dan tahun ini sudah ada.',
            'year.required' => 'Tahun wajib diisi.',
            'year.digits' => 'Tahun harus terdiri dari 4 digit.',
        ]);

        $validated['status'] = 'in_progress';

        UniversityAccreditation::create($validated);

        AlertService::created('Akreditasi universitas berhasil ditambahkan.');

        return redirect()->route('dashboard-admin.university.accreditations.index');
    }

    public function preview(UniversityAccreditation $accreditation)
    {
        $accreditation->load([
            'agency',
            'indicators',
        ]);

        return view('university.accreditation.preview', compact('accreditation'));
    }

    public function destroy(UniversityAccreditation $accreditation)
    {
        $accreditation->delete();

        AlertService::deleted('Akreditasi universitas berhasil dihapus.');

        return redirect()->route('dashboard-admin.university.accreditations.index');
    }

    public function toggleComplete(UniversityAccreditation $accreditation)
    {
        $newStatus = $accreditation->status === 'completed' ? 'in_progress' : 'completed';
        $accreditation->update(['status' => $newStatus]);

        $label = $newStatus === 'completed' ? 'ditandai selesai' : 'dikembalikan ke in-progress';
        AlertService::updated("Akreditasi berhasil {$label}.");

        return redirect()->back();
    }

    public function showCompare()
    {
        $accreditations = UniversityAccreditation::with('agency')
            ->whereHas('indicators')
            ->orderByDesc('year')
            ->get();

        return view('university.accreditation.compare-select', compact('accreditations'));
    }

    public function compare(Request $request)
    {
        $request->validate([
            'accreditation_a' => 'required|exists:university_accreditations,id|different:accreditation_b',
            'accreditation_b' => 'required|exists:university_accreditations,id',
        ], [
            'accreditation_a.required' => 'Pilih Akreditasi A.',
            'accreditation_b.required' => 'Pilih Akreditasi B.',
            'accreditation_a.different' => 'Pilih dua akreditasi yang berbeda.',
        ]);

        $accA = UniversityAccreditation::with(['agency', 'indicators'])->findOrFail($request->accreditation_a);
        $accB = UniversityAccreditation::with(['agency', 'indicators'])->findOrFail($request->accreditation_b);

        $indA = $accA->indicators->keyBy('university_indicator_template_id');
        $indB = $accB->indicators->keyBy('university_indicator_template_id');
        $allTemplateIds = $indA->keys()->merge($indB->keys())->unique()->filter();
        $onlyInA = $accA->indicators->whereNull('university_indicator_template_id');
        $onlyInB = $accB->indicators->whereNull('university_indicator_template_id');

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

        $summaryA = $this->summary($accA->indicators);
        $summaryB = $this->summary($accB->indicators);

        return view('university.accreditation.compare', compact(
            'accA', 'accB', 'rows', 'summaryA', 'summaryB', 'onlyInA', 'onlyInB'
        ));
    }

    private function summary(Collection $indicators): array
    {
        return [
            'total' => $indicators->count(),
            'filled' => $indicators->filter(fn ($i) => $i->result !== null)->count(),
            'achieved' => $indicators->filter(fn ($i) => $i->result !== null && $i->result >= $i->target)->count(),
        ];
    }
}
