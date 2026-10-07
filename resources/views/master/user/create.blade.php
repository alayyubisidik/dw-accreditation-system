@extends('layout.app')

@section('content')
    <div class="container-xl">

        <div class="card">

            <div class="card-header">

                <h3 class="card-title">
                    Tambah Pengguna
                </h3>

                <div class="card-actions">

                    <a href="{{ route('dashboard-admin.master.users.index') }}" class="btn btn-primary">

                        Kembali

                    </a>

                </div>

            </div>

            <div class="card-body">

                <form action="{{ route('dashboard-admin.master.users.store') }}" method="POST" enctype="multipart/form-data">

                    @csrf

                    <div class="row">

                        {{-- Avatar --}}
                        <div class="col-12 mb-3">

                            <label class="form-label">
                                Avatar
                            </label>

                            <x-input-error :messages="$errors->get('avatar')" />

                            <div class="image-upload-wrapper">

                                <div class="image-preview-container">

                                    <img id="avatar-preview" src="{{ asset('uploads/default/avatar.png') }}" alt="Preview">

                                </div>

                                <div class="image-input-container">

                                    <input type="file" name="avatar" id="avatar-upload" accept="image/*">

                                    <p class="text-muted mt-1">
                                        Unggah avatar pengguna
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

                                <input type="text" name="name" class="form-control" value="{{ old('name') }}">

                                <x-input-error :messages="$errors->get('name')" />

                            </div>

                        </div>

                        {{-- Email --}}
                        <div class="col-12">

                            <div class="mb-3">

                                <label class="form-label required">
                                    Email
                                </label>

                                <input type="email" name="email" class="form-control" value="{{ old('email') }}">

                                <x-input-error :messages="$errors->get('email')" />

                            </div>

                        </div>

                        {{-- Role --}}
                        <div class="col-md-6">

                            <div class="mb-3">

                                <label class="form-label required">
                                    Role
                                </label>

                                <select name="role" id="role" class="form-control">

                                    <option value="">
                                        Pilih Role
                                    </option>

                                    <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>
                                        Admin
                                    </option>

                                    <option value="operator" {{ old('role') == 'operator' ? 'selected' : '' }}>
                                        Operator
                                    </option>



                                </select>

                                <x-input-error :messages="$errors->get('role')" />

                            </div>

                        </div>

                        {{-- Fakultas --}}
                        <div class="col-md-6" id="faculty-wrapper">

                            <div class="mb-3">

                                <label class="form-label">
                                    Fakultas
                                </label>

                                <select name="faculty_id" id="faculty_id" class="form-control">

                                    <option value="">
                                        Pilih Fakultas
                                    </option>

                                    @foreach ($faculties as $faculty)
                                        <option value="{{ $faculty->id }}"
                                            {{ old('faculty_id') == $faculty->id ? 'selected' : '' }}>

                                            {{ $faculty->name }}

                                        </option>
                                    @endforeach

                                </select>

                                <x-input-error :messages="$errors->get('faculty_id')" />

                            </div>

                        </div>

                        {{-- Program Studi --}}
                        <div class="col-md-12" id="study-program-wrapper">

                            <div class="mb-3">

                                <label class="form-label">
                                    Program Studi
                                </label>

                                <select name="study_program_id" id="study_program_id" class="form-control">

                                    <option value="">
                                        Pilih Program Studi
                                    </option>

                                    @foreach ($studyPrograms as $studyProgram)
                                        <option value="{{ $studyProgram->id }}"
                                            data-faculty="{{ $studyProgram->faculty_id }}"
                                            {{ old('study_program_id') == $studyProgram->id ? 'selected' : '' }}>

                                            {{ $studyProgram->name }}

                                        </option>
                                    @endforeach

                                </select>

                                <x-input-error :messages="$errors->get('study_program_id')" />

                            </div>

                        </div>

                        {{-- Password --}}
                        <div class="col-md-6">

                            <div class="mb-3 password-group">

                                <label class="form-label required">
                                    Password
                                </label>

                                <div class="password-wrapper">

                                    <input type="password" name="password" class="form-control password-input">

                                    <span class="toggle-password">
                                        <i class="ti ti-eye"></i>
                                    </span>

                                </div>

                                <x-input-error :messages="$errors->get('password')" />

                            </div>

                        </div>

                        {{-- Konfirmasi Password --}}
                        <div class="col-md-6">

                            <div class="mb-3 password-group">

                                <label class="form-label required">
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

                    <div class="card-footer text-end">

                        <button type="submit" class="btn btn-primary">

                            Simpan

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>
@endsection

@push('scripts')
    <script>
        const roleSelect = document.getElementById('role');
        const facultyWrapper = document.getElementById('faculty-wrapper');
        const studyProgramWrapper = document.getElementById('study-program-wrapper');

        function handleRole() {
            const role = roleSelect.value;

            const facultySelect = document.getElementById('faculty_id');
            const studyProgramSelect = document.getElementById('study_program_id');

            // Default: sembunyikan semua
            facultyWrapper.style.display = 'none';
            studyProgramWrapper.style.display = 'none';

            switch (role) {

                case 'admin':
                    // Admin tidak membutuhkan fakultas maupun prodi
                    facultySelect.value = '';
                    studyProgramSelect.value = '';
                    break;



                case 'dekan':
                    // Dekan hanya memilih fakultas
                    facultyWrapper.style.display = 'block';

                    studyProgramSelect.value = '';
                    break;

                case 'operator':
                    // Operator memilih fakultas dan prodi
                    facultyWrapper.style.display = 'block';
                    studyProgramWrapper.style.display = 'block';
                    break;

                default:
                    facultySelect.value = '';
                    studyProgramSelect.value = '';
                    break;
            }

            filterStudyPrograms();
        }

        function filterStudyPrograms() {

            const facultyId = facultySelect.value;

            Array.from(studyProgramSelect.options).forEach(option => {

                if (option.value === '') {
                    option.hidden = false;
                    return;
                }

                if (!facultyId) {
                    option.hidden = true;
                    return;
                }

                option.hidden = option.dataset.faculty != facultyId;
            });

            if (
                studyProgramSelect.selectedOptions.length &&
                studyProgramSelect.selectedOptions[0].hidden
            ) {
                studyProgramSelect.value = '';
            }
        }

        roleSelect.addEventListener('change', handleRole);
        handleRole();


        // Filter prodi berdasarkan fakultas
        const facultySelect = document.getElementById('faculty_id');
        const studyProgramSelect = document.getElementById('study_program_id');

        function filterStudyPrograms() {
            const facultyId = facultySelect.value;

            Array.from(studyProgramSelect.options).forEach(option => {

                if (option.value === '') {
                    option.hidden = false;
                    return;
                }

                option.hidden =
                    option.dataset.faculty !== facultyId;

            });

            if (studyProgramSelect.selectedOptions.length) {
                const selected =
                    studyProgramSelect.selectedOptions[0];

                if (selected.hidden) {
                    studyProgramSelect.value = '';
                }
            }
        }

        facultySelect.addEventListener(
            'change',
            filterStudyPrograms
        );

        filterStudyPrograms();


        // Preview avatar
        document.getElementById('avatar-upload')
            ?.addEventListener('change', function(e) {

                if (!e.target.files.length) {
                    return;
                }

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
