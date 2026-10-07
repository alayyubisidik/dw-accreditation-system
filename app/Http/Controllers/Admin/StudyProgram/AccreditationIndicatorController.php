<?php

namespace App\Http\Controllers\Admin\StudyProgram;

use App\Http\Controllers\Controller;
use App\Models\StudyProgramAccreditation;
use App\Models\StudyProgramAccreditationIndicator;
use App\Models\StudyProgramIndicatorTemplate;
use App\Services\AlertService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AccreditationIndicatorController extends Controller
{
    public function index(Request $request, StudyProgramAccreditation $accreditation)
    {
        $accreditation->load('batch.agency');

        $indicatorsQuery = $accreditation->indicators();

        if ($request->filled('search')) {
            $indicatorsQuery->where('title', 'like', '%' . $request->search . '%');
        }

        $indicators = $indicatorsQuery->get();

        $takenTemplateIds = $accreditation->indicators()
            ->whereNotNull('study_program_indicator_template_id')
            ->pluck('study_program_indicator_template_id');

        $availableTemplates = StudyProgramIndicatorTemplate::query()
            ->where('study_program_accreditation_agency_id', $accreditation->batch->study_program_accreditation_agency_id)
            ->whereNotIn('id', $takenTemplateIds)
            ->get();

        return view('study-program.accreditation-indicator.index', compact(
            'accreditation',
            'indicators',
            'availableTemplates'
        ));
    }

    public function import(Request $request, StudyProgramAccreditation $accreditation)
    {
        $accreditation->load('batch');

        $agencyId = $accreditation->batch->study_program_accreditation_agency_id;

        $takenTemplateIds = $accreditation->indicators()
            ->whereNotNull('study_program_indicator_template_id')
            ->pluck('study_program_indicator_template_id');

        if ($request->boolean('take_all')) {
            $templates = StudyProgramIndicatorTemplate::query()
                ->where('study_program_accreditation_agency_id', $agencyId)
                ->whereNotIn('id', $takenTemplateIds)
                ->get();

            if ($templates->isEmpty()) {
                AlertService::error('Tidak ada indikator yang bisa diambil.');

                return redirect()->route('dashboard-admin.study-program.accreditations.indicators.index', $accreditation);
            }

            foreach ($templates as $template) {
                $accreditation->indicators()->create([
                    'study_program_indicator_template_id' => $template->id,
                    'title'            => $template->title,
                    'description'      => $template->description,
                    'formula_type'     => $template->formula_type,
                    'label_1'          => $template->label_1,
                    'label_2'          => $template->label_2,
                    'target'           => $template->target,
                    'gap_unit'         => $template->gap_unit,
                    'strategic_reason' => $template->strategic_reason,
                ]);
            }

            AlertService::created('Semua indikator berhasil diambil.');

            return redirect()->route('dashboard-admin.study-program.accreditations.indicators.index', $accreditation);
        }

        $validated = $request->validate([
            'study_program_indicator_template_id' => 'required|exists:study_program_indicator_templates,id',
        ], [
            'study_program_indicator_template_id.required' => 'Silakan pilih indikator master terlebih dahulu.',
            'study_program_indicator_template_id.exists'   => 'Indikator master tidak valid.',
        ]);

        $template = StudyProgramIndicatorTemplate::where('study_program_accreditation_agency_id', $agencyId)
            ->whereNotIn('id', $takenTemplateIds)
            ->find($validated['study_program_indicator_template_id']);

        if (!$template) {
            AlertService::error('Indikator sudah diambil atau tidak tersedia untuk lembaga ini.');

            return redirect()->route('dashboard-admin.study-program.accreditations.indicators.index', $accreditation);
        }

        $accreditation->indicators()->create([
            'study_program_indicator_template_id' => $template->id,
            'title'            => $template->title,
            'description'      => $template->description,
            'formula_type'     => $template->formula_type,
            'label_1'          => $template->label_1,
            'label_2'          => $template->label_2,
            'target'           => $template->target,
            'gap_unit'         => $template->gap_unit,
            'strategic_reason' => $template->strategic_reason,
        ]);

        AlertService::created('Indikator berhasil diambil.');

        return redirect()->route('dashboard-admin.study-program.accreditations.indicators.index', $accreditation);
    }

    public function create(StudyProgramAccreditation $accreditation)
    {
        $accreditation->load('batch.agency');

        return view('study-program.accreditation-indicator.create', compact('accreditation'));
    }

    public function store(StudyProgramAccreditation $accreditation, Request $request)
    {
        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('study_program_accreditation_indicators')
                    ->where(fn($query) => $query->where(
                        'study_program_accreditation_id',
                        $accreditation->id
                    )),
            ],
            'description'      => 'nullable|string',
            'formula_type'     => 'required|in:percentage,ratio',
            'label_1'          => 'required|string|max:255',
            'label_2'          => 'nullable|string|max:255',
            'target'           => 'required|numeric|min:0',
            'gap_unit'         => 'nullable|string|max:50',
            'strategic_reason' => 'nullable|string',
        ], [
            'title.required'       => 'Judul indikator wajib diisi.',
            'title.unique'         => 'Judul indikator sudah digunakan.',
            'formula_type.required' => 'Tipe formula wajib dipilih.',
        ]);

        $validated['study_program_accreditation_id'] = $accreditation->id;

        StudyProgramAccreditationIndicator::create($validated);

        AlertService::created('Indikator berhasil ditambahkan.');

        return redirect()->route(
            'dashboard-admin.study-program.accreditations.indicators.index',
            $accreditation
        );
    }

    public function destroy(StudyProgramAccreditation $accreditation, StudyProgramAccreditationIndicator $indicator)
    {
        $indicator->delete();

        AlertService::deleted('Indikator berhasil dihapus.');

        return redirect()->route('dashboard-admin.study-program.accreditations.indicators.index', $accreditation);
    }
}
