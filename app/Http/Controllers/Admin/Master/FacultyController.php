<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Services\AlertService;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FacultyController extends Controller
{
    public function index(Request $request)
    {
        $faculties = Faculty::query()

            ->when($request->search, function ($query) use ($request) {
                $query->where('name', 'like', '%' . $request->search . '%');
            })

            ->latest()
            ->get();

        return view('master.faculty.index', compact('faculties'));
    }

    public function create()
    {
        return view('master.faculty.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:faculties,name',
        ], [
            'name.required' => 'Nama fakultas wajib diisi.',
            'name.unique' => 'Nama fakultas sudah digunakan.',
        ]);

        Faculty::create($validated);

        AlertService::created('Fakultas berhasil ditambahkan.');

        return redirect()->route('dashboard-admin.master.faculties.index');
    }

    public function edit(Faculty $faculty)
    {
        return view('master.faculty.edit', compact('faculty'));
    }

    public function update(Faculty $faculty, Request $request)
    {
        $validated = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('faculties')->ignore($faculty->id)
            ]
        ], [
            'name.required' => 'Nama fakultas wajib diisi.',
            'name.unique' => 'Nama fakultas sudah digunakan.',
        ]);

        $faculty->update($validated);

        AlertService::updated('Fakultas berhasil diperbarui.');

        return redirect()->route('dashboard-admin.master.faculties.index');
    }

    public function destroy(Faculty $faculty)
    {
        if (
            $faculty->studyPrograms()->exists() ||
            $faculty->users()->exists()
        ) {
            AlertService::error(
                'Fakultas tidak dapat dihapus karena masih memiliki data yang terkait.'
            );

            return back();
        }

        $faculty->delete();

        AlertService::deleted('Fakultas berhasil dihapus.');

        return redirect()->route('dashboard-admin.master.faculties.index');
    }
}
