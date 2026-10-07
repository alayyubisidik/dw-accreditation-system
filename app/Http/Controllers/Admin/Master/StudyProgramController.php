<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Models\StudyProgramAccreditation;
use App\Models\StudyProgramAccreditationBatch;
use App\Models\Faculty;
use App\Models\StudyProgram;
use App\Services\AlertService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class StudyProgramController extends Controller
{
    public function index(Request $request)
    {
        $faculties = Faculty::orderBy('name')->get();

        $studyPrograms = StudyProgram::with('faculty')

            ->when($request->search, function ($query) use ($request) {

                $query->where(function ($q) use ($request) {

                    $q->where('name', 'like', '%' . $request->search . '%')

                        ->orWhereHas('faculty', function ($faculty) use ($request) {
                            $faculty->where('name', 'like', '%' . $request->search . '%');
                        });
                });
            })

            ->when($request->faculty_id, function ($query) use ($request) {

                $query->where('faculty_id', $request->faculty_id);
            })

            ->latest()
            ->get();

        return view('master.study-program.index', compact(
            'studyPrograms',
            'faculties'
        ));
    }

    public function create()
    {
        $faculties = Faculty::orderBy('name')->get();

        return view('master.study-program.create', compact('faculties'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'faculty_id' => 'required|exists:faculties,id',
            'name' => 'required|string|max:255|unique:study_programs,name',
        ], [
            'faculty_id.required' => 'Fakultas wajib dipilih.',
            'name.required' => 'Nama program studi wajib diisi.',
            'name.unique' => 'Nama program studi sudah digunakan.',
        ]);

        DB::transaction(function () use ($validated) {

            // Simpan prodi
            $studyProgram = StudyProgram::create($validated);

            // Ambil seluruh batch akreditasi
            $batches = StudyProgramAccreditationBatch::all();

            // Buat accreditation untuk setiap batch
            foreach ($batches as $batch) {
                StudyProgramAccreditation::create([
                    'study_program_accreditation_batch_id' => $batch->id,
                    'study_program_id'                     => $studyProgram->id,
                    'status'                               => 'in_progress',
                ]);
            }
        });

        AlertService::created('Program Studi berhasil ditambahkan.');

        return redirect()->route('dashboard-admin.master.study-programs.index');
    }

    public function edit(StudyProgram $studyProgram)
    {
        $faculties = Faculty::orderBy('name')->get();

        return view('master.study-program.edit', compact(
            'studyProgram',
            'faculties'
        ));
    }

    public function update(StudyProgram $studyProgram, Request $request)
    {
        $validated = $request->validate([
            'faculty_id' => 'required|exists:faculties,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('study_programs')->ignore($studyProgram->id)
            ],
        ], [
            'faculty_id.required' => 'Fakultas wajib dipilih.',
            'name.required' => 'Nama program studi wajib diisi.',
            'name.unique' => 'Nama program studi sudah digunakan.',
        ]);

        $studyProgram->update($validated);

        AlertService::updated('Program Studi berhasil diperbarui.');

        return redirect()->route('dashboard-admin.master.study-programs.index');
    }

    public function destroy(StudyProgram $studyProgram)
    {
        if (
            $studyProgram->users()->exists() ||
            $studyProgram->studyProgramAccreditations()->exists()
        ) {
            AlertService::error(
                'Program Studi tidak dapat dihapus karena masih memiliki data yang terkait.'
            );

            return back();
        }

        $studyProgram->delete();

        AlertService::deleted('Program Studi berhasil dihapus.');

        return redirect()->route('dashboard-admin.master.study-programs.index');
    }
}
