<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Models\Faculty;
use App\Models\StudyProgram;
use App\Models\User;
use App\Services\AlertService;
use App\Traits\FileUploadTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    use FileUploadTrait;

    public function index(Request $request)
    {
        $users = User::with([
            'studyProgram.faculty',
        ])

            ->when($request->search, function ($query) use ($request) {

                $query->where(function ($q) use ($request) {

                    $q->where('name', 'like', '%' . $request->search . '%')
                        ->orWhere('email', 'like', '%' . $request->search . '%');
                });
            })

            ->when($request->role, function ($query) use ($request) {

                $query->where('role', $request->role);
            })

            ->when(
                $request->status !== null &&
                    $request->status !== '',
                function ($query) use ($request) {

                    $query->where(
                        'is_active',
                        $request->status
                    );
                }
            )

            ->latest()
            ->get();

        return view('master.user.index', compact(
            'users'
        ));
    }

    public function create()
    {
        $faculties = Faculty::orderBy('name')->get();

        $studyPrograms = StudyProgram::with('faculty')
            ->orderBy('name')
            ->get();

        return view('master.user.create', compact(
            'faculties',
            'studyPrograms'
        ));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([

            'study_program_id' => [
                Rule::requiredIf($request->role === 'operator'),
                'nullable',
                'exists:study_programs,id'
            ],

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'email' => [
                'required',
                'email',
                'unique:users,email'
            ],

            'role' => [
                'required',
                Rule::in([
                    'admin',
                    'operator',
                ])
            ],

            'password' => [
                'required',
                'confirmed',
                'min:4'
            ],

            'avatar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],

        ], [

            'study_program_id.required' => 'Program studi wajib dipilih untuk operator.',
            'study_program_id.exists' => 'Program studi tidak ditemukan.',

            'name.required' => 'Nama pengguna wajib diisi.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',

            'role.required' => 'Role wajib dipilih.',

            'password.required' => 'Password wajib diisi.',
            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'password.min' => 'Password minimal 4 karakter.',

        ]);

        if ($request->hasFile('avatar')) {

            $validated['avatar'] = $this->uploadFile(
                $request->file('avatar'),
                null,
                'user-avatar'
            );
        }

        $validated['password'] = Hash::make(
            $validated['password']
        );

        User::create($validated);

        AlertService::created(
            'Pengguna berhasil ditambahkan.'
        );

        return redirect()->route('dashboard-admin.master.users.index');
    }

    public function edit(User $user)
    {
        $faculties = Faculty::orderBy('name')->get();

        $studyPrograms = StudyProgram::with('faculty')
            ->orderBy('name')
            ->get();

        return view('master.user.edit', compact(
            'user',
            'faculties',
            'studyPrograms'
        ));
    }

    public function update(
        Request $request,
        User $user
    ) {
        $validated = $request->validate([

            'study_program_id' => [
                Rule::requiredIf($request->role === 'operator'),
                'nullable',
                'exists:study_programs,id'
            ],

            'name' => [
                'required',
                'string',
                'max:255'
            ],

            'email' => [
                'required',
                'email',
                Rule::unique('users')
                    ->ignore($user->id)
            ],

            'role' => [
                'required',
                Rule::in([
                    'admin',
                    'operator',
                ])
            ],

            'password' => [
                'nullable',
                'confirmed',
                'min:4'
            ],

            'avatar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],

        ], [

            'study_program_id.exists' => 'Program studi tidak ditemukan.',

            'name.required' => 'Nama pengguna wajib diisi.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',

            'role.required' => 'Role wajib dipilih.',

            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'password.min' => 'Password minimal 4 karakter.',

        ]);

        if ($request->hasFile('avatar')) {

            $validated['avatar'] = $this->uploadFile(
                $request->file('avatar'),
                $user->avatar,
                'user-avatar'
            );
        }

        if (!empty($validated['password'])) {

            $validated['password'] = Hash::make(
                $validated['password']
            );
        } else {

            unset($validated['password']);
        }

        $user->update($validated);

        AlertService::updated(
            'Pengguna berhasil diperbarui.'
        );

        return redirect()->route('dashboard-admin.master.users.index');
    }

    public function destroy(User $user)
    {
        if (user()->id == $user->id) {

            AlertService::error(
                'Tidak dapat menghapus akun yang sedang digunakan.'
            );

            return back();
        }

        if (
            $user->avatar &&
            $user->avatar !== '/uploads/default/avatar.png'
        ) {
            $this->deleteFile($user->avatar);
        }

        $user->delete();

        AlertService::deleted(
            'Pengguna berhasil dihapus.'
        );

        return redirect()->route('dashboard-admin.master.users.index');
    }

    public function toggleStatus(
        Request $request,
        User $user
    ) {
        if (user()->id == $user->id) {

            AlertService::error(
                'Tidak dapat mengubah status akun yang sedang digunakan.'
            );

            return back();
        }

        $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $user->update([
            'is_active' => $request->is_active
        ]);

        if ($request->is_active) {

            AlertService::updated(
                'Pengguna berhasil diaktifkan.'
            );
        } else {

            AlertService::updated(
                'Pengguna berhasil dinonaktifkan.'
            );
        }

        return back();
    }
}
