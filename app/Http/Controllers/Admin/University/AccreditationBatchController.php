<?php

namespace App\Http\Controllers\Admin\University;

use App\Http\Controllers\Controller;
use App\Models\UniversityAccreditation;
use App\Models\UniversityAccreditationAgency;
use App\Models\UniversityAccreditationBatch;
use App\Models\UniversityAccreditationIndicator;
use App\Models\UniversityIndicatorFile;
use App\Services\AlertService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AccreditationBatchController extends Controller
{
    public function index(Request $request)
    {
        $batches = UniversityAccreditationBatch::query()

            ->with('agency')
            ->withCount('universityAccreditation')

            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhereHas('agency', function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%');
                    });
            })

            ->latest()
            ->get();

        return view('university.accreditation-batch.index', compact('batches'));
    }

    public function create()
    {
        $agencies = UniversityAccreditationAgency::orderBy('name')->get();

        return view('university.accreditation-batch.create', compact('agencies'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'university_accreditation_agency_id' => 'required|exists:university_accreditation_agencies,id',
            'name'        => 'required|string|max:255',
            'year'        => 'required|digits:4|integer|min:2000|max:2100',
            'description' => 'nullable|string',
        ], [
            'university_accreditation_agency_id.required' => 'Lembaga akreditasi wajib dipilih.',
            'university_accreditation_agency_id.exists'   => 'Lembaga akreditasi tidak valid.',
            'name.required' => 'Nama batch wajib diisi.',
            'year.required' => 'Tahun wajib diisi.',
            'year.digits'   => 'Tahun harus terdiri dari 4 digit.',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        DB::transaction(function () use ($validated) {

            $batch = UniversityAccreditationBatch::create($validated);

            // Buat satu record akreditasi universitas untuk batch ini
            UniversityAccreditation::create([
                'university_accreditation_batch_id' => $batch->id,
                'status'                            => 'in_progress',
            ]);
        });

        AlertService::created('Batch akreditasi berhasil ditambahkan beserta data akreditasi universitas.');

        return redirect()->route('dashboard-admin.university.accreditation-batches.index');
    }

    public function edit(UniversityAccreditationBatch $accreditationBatch)
    {
        $agencies = UniversityAccreditationAgency::orderBy('name')->get();

        return view('university.accreditation-batch.edit', compact('accreditationBatch', 'agencies'));
    }

    public function update(Request $request, UniversityAccreditationBatch $accreditationBatch)
    {
        $validated = $request->validate([
            'university_accreditation_agency_id' => 'required|exists:university_accreditation_agencies,id',
            'name'        => 'required|string|max:255',
            'year'        => 'required|digits:4|integer|min:2000|max:2100',
            'description' => 'nullable|string',
        ], [
            'university_accreditation_agency_id.required' => 'Lembaga akreditasi wajib dipilih.',
            'university_accreditation_agency_id.exists'   => 'Lembaga akreditasi tidak valid.',
            'name.required' => 'Nama batch wajib diisi.',
            'year.required' => 'Tahun wajib diisi.',
            'year.digits'   => 'Tahun harus terdiri dari 4 digit.',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $accreditationBatch->update($validated);

        AlertService::updated('Batch akreditasi berhasil diperbarui.');

        return redirect()->route('dashboard-admin.university.accreditation-batches.index');
    }

    public function destroy(UniversityAccreditationBatch $accreditationBatch)
    {
        DB::transaction(function () use ($accreditationBatch) {

            $accreditation = $accreditationBatch->universityAccreditation;

            if ($accreditation) {

                $indicatorIds = UniversityAccreditationIndicator::where(
                    'university_accreditation_id',
                    $accreditation->id
                )->pluck('id');

                UniversityIndicatorFile::whereIn('university_accreditation_indicator_id', $indicatorIds)
                    ->get()
                    ->each(function ($file) {
                        Storage::disk('public')->delete($file->file_path);
                    });

                UniversityIndicatorFile::whereIn('university_accreditation_indicator_id', $indicatorIds)->delete();

                UniversityAccreditationIndicator::where('university_accreditation_id', $accreditation->id)->delete();

                $accreditation->delete();
            }

            $accreditationBatch->delete();
        });

        AlertService::deleted('Batch akreditasi beserta seluruh data akreditasi terkait berhasil dihapus.');

        return redirect()->route('dashboard-admin.university.accreditation-batches.index');
    }
}
