@extends('layout.app')

@section('content')
    <div class="container-xl">

        <div class="card">

            <div class="card-header">

                <h3 class="card-title">
                    Profil Saya
                </h3>

            </div>

            <div class="card-body">

                <form action="{{ route('profiles.update') }}" method="POST" enctype="multipart/form-data">

                    @csrf
                    @method('PUT')

                    <div class="row">

                        {{-- Avatar --}}
                        <div class="col-12 mb-3">

                            <label class="form-label">
                                Avatar
                            </label>

                            <x-input-error :messages="$errors->get('avatar')" />

                            <div class="image-upload-wrapper">

                                <div class="image-preview-container">

                                    <img id="avatar-preview" src="{{ asset($user->avatar) }}" alt="Preview">

                                </div>

                                <div class="image-input-container">

                                    <input type="file" name="avatar" id="avatar-upload" accept="image/*">

                                    <p class="text-muted mt-1">
                                        Unggah avatar baru jika ingin mengganti avatar
                                    </p>

                                </div>

                            </div>

                        </div>

                        {{-- Nama --}}
                        <div class="col-12">

                            <div class="mb-3">

                                <label class="form-label required">
                                    Nama
                                </label>

                                <input type="text" name="name" class="form-control"
                                    value="{{ old('name', $user->name) }}">

                                <x-input-error :messages="$errors->get('name')" />

                            </div>

                        </div>

                        {{-- Email --}}
                        <div class="col-12">

                            <div class="mb-3">

                                <label class="form-label required">
                                    Email
                                </label>

                                <input type="email" name="email" class="form-control"
                                    value="{{ old('email', $user->email) }}">

                                <x-input-error :messages="$errors->get('email')" />

                            </div>

                        </div>

                        {{-- Role (read-only, info saja) --}}
                        <div class="col-md-4">

                            <div class="mb-3">

                                <label class="form-label">
                                    Role
                                </label>

                                <input type="text" class="form-control" value="{{ ucfirst($user->role) }}" disabled>

                                <small class="text-muted">
                                    Role hanya dapat diubah oleh admin.
                                </small>

                            </div>

                        </div>

                        {{-- Fakultas (read-only, hanya tampil jika ada) --}}
                        @if ($user->faculty)
                            <div class="col-md-4">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Fakultas
                                    </label>

                                    <input type="text" class="form-control" value="{{ $user->faculty->name }}" disabled>

                                </div>

                            </div>
                        @endif

                        {{-- Program Studi (read-only, hanya tampil jika ada) --}}
                        @if ($user->studyProgram)
                            <div class="col-md-4">

                                <div class="mb-3">

                                    <label class="form-label">
                                        Program Studi
                                    </label>

                                    <input type="text" class="form-control" value="{{ $user->studyProgram->name }}"
                                        disabled>

                                </div>

                            </div>
                        @endif

                        {{-- Password Baru --}}
                        <div class="col-md-6">

                            <div class="mb-3 password-group">

                                <label class="form-label">
                                    Password Baru
                                </label>

                                <div class="password-wrapper">

                                    <input type="password" name="password" class="form-control password-input">

                                    <span class="toggle-password">
                                        <i class="ti ti-eye"></i>
                                    </span>

                                </div>

                                <small class="text-muted">
                                    Kosongkan jika tidak ingin mengganti password.
                                </small>

                                <x-input-error :messages="$errors->get('password')" />

                            </div>

                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="col-md-6">

                            <div class="mb-3 password-group">

                                <label class="form-label">
                                    Konfirmasi Password
                                </label>

                                <div class="password-wrapper">

                                    <input type="password" name="password_confirmation" class="form-control password-input">

                                    <span class="toggle-password">
                                        <i class="ti ti-eye"></i>
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="card-footer d-flex justify-content-end gap-3">

                        <a href="/" class="btn btn-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a>

                        <button type="submit" class="btn btn-primary">
                            Simpan Perubahan
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
@endsection


@push('scripts')
    <script>
        // Preview avatar
        document.getElementById('avatar-upload')
            .addEventListener('change', function(e) {

                const reader = new FileReader();

                reader.onload = function(event) {
                    document.getElementById('avatar-preview')
                        .src = event.target.result;
                };

                reader.readAsDataURL(e.target.files[0]);

            });


        // Show / hide password
        document.querySelectorAll('.toggle-password')
            .forEach(function(toggle) {

                toggle.addEventListener('click', function() {

                    const input =
                        this.closest('.password-wrapper')
                        .querySelector('.password-input');

                    input.type =
                        input.type === 'password' ?
                        'text' :
                        'password';

                });

            });
    </script>
@endpush
