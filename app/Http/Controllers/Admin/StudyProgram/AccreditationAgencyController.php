<?php

namespace App\Http\Controllers\Admin\StudyProgram;

use App\Http\Controllers\Controller;
use App\Models\StudyProgramAccreditationAgency;
use App\Services\AlertService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AccreditationAgencyController extends Controller
{
    public function index(Request $request)
    {
        $agencies = StudyProgramAccreditationAgency::query()

            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            })

            ->latest()
            ->get();

        return view(
            'study-program.accreditation-agency.index',
            compact('agencies')
        );
    }

    public function create()
    {
        return view('study-program.accreditation-agency.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:study_program_accreditation_agencies,name',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Nama lembaga akreditasi wajib diisi.',
            'name.unique' => 'Nama lembaga akreditasi sudah digunakan.',
        ]);

        StudyProgramAccreditationAgency::create($validated);

        AlertService::created('Lembaga akreditasi berhasil ditambahkan.');

        return redirect()->route('dashboard-admin.study-program.accreditation-agencies.index');
    }

    public function edit(StudyProgramAccreditationAgency $accreditationAgency)
    {
        return view(
            'study-program.accreditation-agency.edit',
            compact('accreditationAgency')
        );
    }

    public function update(
        StudyProgramAccreditationAgency $accreditationAgency,
        Request $request
    ) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('study_program_accreditation_agencies')
                    ->ignore($accreditationAgency->id),
            ],
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Nama lembaga akreditasi wajib diisi.',
            'name.unique' => 'Nama lembaga akreditasi sudah digunakan.',
        ]);

        $accreditationAgency->update($validated);

        AlertService::updated('Lembaga akreditasi berhasil diperbarui.');

        return redirect()->route('dashboard-admin.study-program.accreditation-agencies.index');
    }

    public function destroy(StudyProgramAccreditationAgency $accreditationAgency)
    {
        if ($accreditationAgency->studyProgramAccreditationBatches()->exists()) {

            AlertService::error(
                'Lembaga akreditasi tidak dapat dihapus karena masih digunakan pada data akreditasi.'
            );

            return back();
        }

        $accreditationAgency->delete();

        AlertService::deleted('Lembaga akreditasi berhasil dihapus.');

        return redirect()->route('dashboard-admin.study-program.accreditation-agencies.index');
    }
}
