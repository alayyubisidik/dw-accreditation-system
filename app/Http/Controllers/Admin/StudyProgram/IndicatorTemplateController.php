<?php

namespace App\Http\Controllers\Admin\StudyProgram;

use App\Http\Controllers\Controller;
use App\Models\StudyProgramAccreditationAgency;
use App\Models\StudyProgramAccreditationIndicator;
use App\Models\StudyProgramIndicatorTemplate;
use App\Services\AlertService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class IndicatorTemplateController extends Controller
{
    public function index(StudyProgramAccreditationAgency $accreditationAgency, Request $request)
    {
        $indicatorTemplates = $accreditationAgency
            ->studyProgramIndicatorTemplates()
            ->when($request->filled('search'), function ($query) use ($request) {
                $query->where('title', 'like', '%' . $request->search . '%');
            })
            ->orderBy('id')
            ->get();

        return view(
            'study-program.indicator-template.index',
            compact('indicatorTemplates', 'accreditationAgency')
        );
    }

    public function create(StudyProgramAccreditationAgency $accreditationAgency)
    {
        return view(
            'study-program.indicator-template.create',
            compact('accreditationAgency')
        );
    }

    public function store(StudyProgramAccreditationAgency $accreditationAgency, Request $request)
    {
        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('study_program_indicator_templates')
                    ->where(fn($query) => $query->where(
                        'study_program_accreditation_agency_id',
                        $accreditationAgency->id
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

        $validated['study_program_accreditation_agency_id'] = $accreditationAgency->id;

        StudyProgramIndicatorTemplate::create($validated);

        AlertService::created('Template indikator berhasil ditambahkan.');

        return redirect()->route(
            'dashboard-admin.study-program.accreditation-agencies.indicator-templates.index',
            $accreditationAgency
        );
    }

    public function edit(
        StudyProgramAccreditationAgency $accreditationAgency,
        StudyProgramIndicatorTemplate $indicatorTemplate
    ) {
        return view(
            'study-program.indicator-template.edit',
            compact('accreditationAgency', 'indicatorTemplate')
        );
    }

    public function update(
        StudyProgramAccreditationAgency $accreditationAgency,
        StudyProgramIndicatorTemplate $indicatorTemplate,
        Request $request
    ) {
        $validated = $request->validate([
            'title' => [
                'required',
                'string',
                'max:255',
                Rule::unique('study_program_indicator_templates')
                    ->where(fn($query) => $query->where(
                        'study_program_accreditation_agency_id',
                        $accreditationAgency->id
                    ))
                    ->ignore($indicatorTemplate->id),
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

        DB::transaction(function () use ($indicatorTemplate, $validated) {

            $indicatorTemplate->update($validated);

            // Sinkronkan seluruh indikator yang berasal dari template ini
            StudyProgramAccreditationIndicator::where(
                'study_program_indicator_template_id',
                $indicatorTemplate->id
            )->update([
                'title'            => $validated['title'],
                'description'      => $validated['description'],
                'formula_type'     => $validated['formula_type'],
                'label_1'          => $validated['label_1'],
                'label_2'          => $validated['label_2'],
                'target'           => $validated['target'],
                'gap_unit'         => $validated['gap_unit'],
                'strategic_reason' => $validated['strategic_reason'],
                'updated_at'       => now(),
            ]);
        });

        AlertService::updated('Template indikator berhasil diperbarui.');

        return redirect()->route(
            'dashboard-admin.study-program.accreditation-agencies.indicator-templates.index',
            $accreditationAgency
        );
    }

    public function destroy(
        StudyProgramAccreditationAgency $accreditationAgency,
        StudyProgramIndicatorTemplate $indicatorTemplate
    ) {
        if ($indicatorTemplate->studyProgramAccreditationIndicators()->exists()) {
            AlertService::error(
                'Template indikator tidak dapat dihapus karena sudah digunakan.'
            );

            return back();
        }

        $indicatorTemplate->delete();

        AlertService::deleted('Template indikator berhasil dihapus.');

        return redirect()->route(
            'dashboard-admin.study-program.accreditation-agencies.indicator-templates.index',
            $accreditationAgency
        );
    }
}
