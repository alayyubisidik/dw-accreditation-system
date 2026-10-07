<?php

namespace App\Http\Controllers\Admin\University;

use App\Http\Controllers\Controller;
use App\Models\UniversityAccreditationAgency;
use App\Services\AlertService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class AccreditationAgencyController extends Controller
{
    public function index(Request $request)
    {
        $agencies = UniversityAccreditationAgency::query()

            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhere('description', 'like', '%' . $request->search . '%');
            })

            ->latest()
            ->get();

        return view(
            'university.accreditation-agency.index',
            compact('agencies')
        );
    }

    public function create()
    {
        return view('university.accreditation-agency.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:university_accreditation_agencies,name',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Nama lembaga akreditasi wajib diisi.',
            'name.unique' => 'Nama lembaga akreditasi sudah digunakan.',
        ]);

        UniversityAccreditationAgency::create($validated);

        AlertService::created('Lembaga akreditasi berhasil ditambahkan.');

        return redirect()->route('dashboard-admin.university.accreditation-agencies.index');
    }

    public function edit(UniversityAccreditationAgency $accreditationAgency)
    {
        return view(
            'university.accreditation-agency.edit',
            compact('accreditationAgency')
        );
    }

    public function update(
        UniversityAccreditationAgency $accreditationAgency,
        Request $request
    ) {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('university_accreditation_agencies')
                    ->ignore($accreditationAgency->id),
            ],
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Nama lembaga akreditasi wajib diisi.',
            'name.unique' => 'Nama lembaga akreditasi sudah digunakan.',
        ]);

        $accreditationAgency->update($validated);

        AlertService::updated('Lembaga akreditasi berhasil diperbarui.');

        return redirect()->route('dashboard-admin.university.accreditation-agencies.index');
    }

    public function destroy(UniversityAccreditationAgency $accreditationAgency)
    {
        if ($accreditationAgency->universityAccreditationBatches()->exists()) {

            AlertService::error(
                'Lembaga akreditasi tidak dapat dihapus karena masih digunakan pada data akreditasi.'
            );

            return back();
        }

        $accreditationAgency->delete();

        AlertService::deleted('Lembaga akreditasi berhasil dihapus.');

        return redirect()->route('dashboard-admin.university.accreditation-agencies.index');
    }
}
