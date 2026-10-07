<?php

namespace App\Http\Controllers\Admin\StudyProgram;

use App\Http\Controllers\Controller;
use App\Models\StudyProgram;
use App\Models\StudyProgramAccreditationBatch;
use App\Models\StudyProgramAccreditationIndicator;
use App\Models\StudyProgramIndicatorFile;
use App\Services\AlertService;
use App\Services\StudyProgramBatchEnrollmentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class AccreditationBatchController extends Controller
{
    public function index(Request $request)
    {
        $batches = StudyProgramAccreditationBatch::query()

            ->with('agency')
            ->withCount('studyProgramAccreditations')

            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%')
                    ->orWhereHas('agency', function ($q) use ($request) {
                        $q->where('name', 'like', '%' . $request->search . '%');
                    });
            })

            ->latest()
            ->get();

        return view('study-program.accreditation-batch.index', compact('batches'));
    }

    public function create()
    {
        $agencies = \App\Models\StudyProgramAccreditationAgency::orderBy('name')->get();
        $studyPrograms = \App\Models\StudyProgram::orderBy('name')->get();

        return view('study-program.accreditation-batch.create', compact('agencies', 'studyPrograms'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'study_program_accreditation_agency_id' => 'required|exists:study_program_accreditation_agencies,id',
            'name'        => 'required|string|max:255',
            'year'        => 'required|digits:4|integer|min:2000|max:2100',
            'description' => 'nullable|string',
        ], [
            'study_program_accreditation_agency_id.required' => 'Lembaga akreditasi wajib dipilih.',
            'study_program_accreditation_agency_id.exists'   => 'Lembaga akreditasi tidak valid.',
            'name.required' => 'Nama batch wajib diisi.',
            'year.required' => 'Tahun wajib diisi.',
            'year.digits'   => 'Tahun harus terdiri dari 4 digit.',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        StudyProgramAccreditationBatch::create($validated);

        AlertService::created('Batch akreditasi berhasil ditambahkan. Tambahkan program studi peserta melalui aksi Tambah Prodi.');

        return redirect()->route('dashboard-admin.study-program.accreditation-batches.index');
    }


    public function edit(StudyProgramAccreditationBatch $accreditationBatch)
    {
        $agencies = \App\Models\StudyProgramAccreditationAgency::orderBy('name')->get();

        return view('study-program.accreditation-batch.edit', compact('accreditationBatch', 'agencies'));
    }

    public function update(Request $request, StudyProgramAccreditationBatch $accreditationBatch)
    {
        $validated = $request->validate([
            'study_program_accreditation_agency_id' => 'required|exists:study_program_accreditation_agencies,id',
            'name'        => 'required|string|max:255',
            'year'        => 'required|digits:4|integer|min:2000|max:2100',
            'description' => 'nullable|string',
        ], [
            'study_program_accreditation_agency_id.required' => 'Lembaga akreditasi wajib dipilih.',
            'study_program_accreditation_agency_id.exists'   => 'Lembaga akreditasi tidak valid.',
            'name.required' => 'Nama batch wajib diisi.',
            'year.required' => 'Tahun wajib diisi.',
            'year.digits'   => 'Tahun harus terdiri dari 4 digit.',
        ]);

        $validated['is_active'] = $request->boolean('is_active');

        $accreditationBatch->update($validated);

        AlertService::updated('Batch akreditasi berhasil diperbarui.');

        return redirect()->route('dashboard-admin.study-program.accreditation-batches.index');
    }

    public function destroy(StudyProgramAccreditationBatch $accreditationBatch)
    {
        DB::transaction(function () use ($accreditationBatch) {

            $accreditationIds = $accreditationBatch->studyProgramAccreditations()->pluck('id');

            $indicatorIds = StudyProgramAccreditationIndicator::whereIn('study_program_accreditation_id', $accreditationIds)
                ->pluck('id');

            StudyProgramIndicatorFile::whereIn('study_program_accreditation_indicator_id', $indicatorIds)
                ->get()
                ->each(function ($file) {
                    Storage::disk('public')->delete($file->file_path);
                });

            StudyProgramIndicatorFile::whereIn('study_program_accreditation_indicator_id', $indicatorIds)->delete();

            StudyProgramAccreditationIndicator::whereIn('study_program_accreditation_id', $accreditationIds)->delete();

            $accreditationBatch->studyProgramAccreditations()->delete();

            $accreditationBatch->delete();
        });

        AlertService::deleted('Batch akreditasi beserta seluruh data akreditasi terkait berhasil dihapus.');

        return redirect()->route('dashboard-admin.study-program.accreditation-batches.index');
    }

    /**
     * Tampilkan form pilih prodi untuk didaftarkan ke batch.
     *
     * GET dashboard-admin/study-program/accreditation-batches/{accreditationBatch}/enroll
     */
    public function showEnroll(StudyProgramAccreditationBatch $accreditationBatch)
    {
        $batch = $accreditationBatch->load('agency');

        $studyPrograms = StudyProgram::with('faculty')->orderBy('name')->get();

        $enrolledIds = $accreditationBatch
            ->studyProgramAccreditations()
            ->pluck('study_program_id');

        return view(
            'study-program.accreditation-batch.enroll',
            compact('batch', 'studyPrograms', 'enrolledIds')
        );
    }

    /**
     * Daftarkan satu atau lebih prodi ke dalam batch.
     *
     * POST dashboard-admin/study-program/accreditation-batches/{accreditationBatch}/enroll
     * Body: study_program_ids[] => array of study_program_id integers
     */
    public function enroll(Request $request, StudyProgramAccreditationBatch $accreditationBatch)
    {
        $validated = $request->validate([
            'study_program_ids'   => 'required|array|min:1',
            'study_program_ids.*' => 'required|integer|exists:study_programs,id',
        ], [
            'study_program_ids.required'   => 'Pilih minimal satu program studi.',
            'study_program_ids.*.exists'   => 'Program studi tidak valid.',
        ]);

        (new StudyProgramBatchEnrollmentService())->enroll(
            $accreditationBatch,
            $validated['study_program_ids']
        );

        AlertService::created('Program studi berhasil didaftarkan ke batch akreditasi.');

        return redirect()->route('dashboard-admin.study-program.accreditation-batches.index');
    }
}
