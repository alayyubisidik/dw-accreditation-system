<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use App\Services\AlertService;
use App\Traits\FileUploadTrait;

class ProfileController extends Controller
{
    use FileUploadTrait;

    public function edit()
    {
        // Selalu pakai user yang sedang login,
        // jangan pernah ambil dari parameter route.
        $user = Auth::user();

        return view('profile.edit', compact('user'));
    }

    public function update(Request $request)
    {
        $user = user();

        $validated = $request->validate([

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

            'password' => [
                'nullable',
                'confirmed',
                'min:8'
            ],

            'avatar' => [
                'nullable',
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:2048'
            ],

        ], [

            'name.required' => 'Nama wajib diisi.',

            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',

            'password.confirmed' => 'Konfirmasi password tidak sesuai.',
            'password.min' => 'Password minimal 8 karakter.',

            'avatar.image' => 'File harus berupa gambar.',
            'avatar.mimes' => 'Format gambar harus jpg, jpeg, png, atau webp.',
            'avatar.max' => 'Ukuran gambar maksimal 2MB.',

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

        // Jaga-jaga: pastikan field terlarang tidak pernah
        // ikut ter-mass-assign meskipun request memuatnya.
        unset(
            $validated['role'],
            $validated['faculty_id'],
            $validated['study_program_id']
        );

        $user->update($validated);

        AlertService::updated(
            'Profil berhasil diperbarui.'
        );

        return redirect()->route('profiles.edit');
    }
}