    @extends('layout.app')

    @section('content')

    <div class="container-xl">

        <div class="card">

            <div class="card-header">

                <h3 class="card-title">
                    Ubah Pengguna
                </h3>

                <div class="card-actions">

                    <a href="{{ route('dashboard-admin.master.users.index') }}"
                    class="btn btn-primary">

                        Kembali

                    </a>

                </div>

            </div>

            <div class="card-body">

                <form action="{{ route('dashboard-admin.master.users.update', $user) }}"
                    method="POST"
                    enctype="multipart/form-data">

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

                                    <img id="avatar-preview"
                                        src="{{ asset($user->avatar) }}"
                                        alt="Preview">

                                </div>

                                <div class="image-input-container">

                                    <input type="file"
                                        name="avatar"
                                        id="avatar-upload"
                                        accept="image/*">

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

                                <input type="text"
                                    name="name"
                                    class="form-control"
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

                                <input type="email"
                                    name="email"
                                    class="form-control"
                                    value="{{ old('email', $user->email) }}">

                                <x-input-error :messages="$errors->get('email')" />

                            </div>

                        </div>

                        {{-- Role --}}
                        <div class="col-md-6">

                            <div class="mb-3">

                                <label class="form-label required">
                                    Role
                                </label>

                                <select name="role"
                                        id="role"
                                        class="form-control">

                                    <option value="">
                                        Pilih Role
                                    </option>

                                    <option value="admin"
                                        {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>
                                        Admin
                                    </option>

                                    <option value="operator"
                                        {{ old('role', $user->role) == 'operator' ? 'selected' : '' }}>
                                        Operator
                                    </option>



                                </select>

                                <x-input-error :messages="$errors->get('role')" />

                            </div>

                        </div>

                        {{-- Fakultas --}}
                        <div class="col-md-6"
                            id="faculty-wrapper">

                            <div class="mb-3">

                                <label class="form-label">
                                    Fakultas
                                </label>

                                <select name="faculty_id"
                                        id="faculty_id"
                                        class="form-control">

                                    <option value="">
                                        Pilih Fakultas
                                    </option>

                                    @foreach($faculties as $faculty)

                                        <option value="{{ $faculty->id }}"
                                            {{ old('faculty_id', $user->studyProgram?->faculty_id) == $faculty->id ? 'selected' : '' }}>

                                            {{ $faculty->name }}

                                        </option>

                                    @endforeach

                                </select>

                                <x-input-error :messages="$errors->get('faculty_id')" />

                            </div>

                        </div>

                        {{-- Program Studi --}}
                        <div class="col-md-12"
                            id="study-program-wrapper">

                            <div class="mb-3">

                                <label class="form-label">
                                    Program Studi
                                </label>

                                <select name="study_program_id"
                                        id="study_program_id"
                                        class="form-control">

                                    <option value="">
                                        Pilih Program Studi
                                    </option>

                                    @foreach($studyPrograms as $studyProgram)

                                        <option
                                            value="{{ $studyProgram->id }}"
                                            data-faculty="{{ $studyProgram->faculty_id }}"
                                            {{ old('study_program_id', $user->study_program_id) == $studyProgram->id ? 'selected' : '' }}>

                                            {{ $studyProgram->name }}

                                        </option>

                                    @endforeach

                                </select>

                                <x-input-error :messages="$errors->get('study_program_id')" />

                            </div>

                        </div>

                        {{-- Password Baru --}}
                        <div class="col-md-6">

                            <div class="mb-3 password-group">

                                <label class="form-label">
                                    Password Baru
                                </label>

                                <div class="password-wrapper">

                                    <input type="password"
                                        name="password"
                                        class="form-control password-input">

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

                                    <input type="password"
                                        name="password_confirmation"
                                        class="form-control password-input">

                                    <span class="toggle-password">
                                        <i class="ti ti-eye"></i>
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>

                    <div class="card-footer text-end">

                        <button type="submit"
                                class="btn btn-primary">

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

    const roleSelect = document.getElementById('role');
    const facultySelect = document.getElementById('faculty_id');
    const studyProgramSelect = document.getElementById('study_program_id');
    const facultyWrapper = document.getElementById('faculty-wrapper');
    const studyProgramWrapper = document.getElementById('study-program-wrapper');

    const studyProgramOptions = Array.from(studyProgramSelect.options).map(option => ({
        value: option.value,
        text: option.text,
        facultyId: option.dataset.faculty || ''
    }));

    function filterStudyPrograms(preserveSelected = false)
    {
        const facultyId = facultySelect.value;
        const selectedVal = preserveSelected ? studyProgramSelect.value : '';

        studyProgramSelect.innerHTML = '<option value="">Pilih Program Studi</option>';

        if(!facultyId)
        {
            return;
        }

        studyProgramOptions.forEach(item => {
            if(item.value && String(item.facultyId) === String(facultyId))
            {
                const option = document.createElement('option');
                option.value = item.value;
                option.text = item.text;
                option.dataset.faculty = item.facultyId;
                if(item.value === selectedVal)
                {
                    option.selected = true;
                }
                studyProgramSelect.appendChild(option);
            }
        });
    }

    function handleRole()
    {
        const role = roleSelect.value;

        facultyWrapper.style.display = 'none';
        studyProgramWrapper.style.display = 'none';

        if(role === 'operator')
        {
            facultyWrapper.style.display = 'block';
            studyProgramWrapper.style.display = 'block';
        }
        else
        {
            facultySelect.value = '';
            studyProgramSelect.value = '';
            filterStudyPrograms(false);
        }
    }

    roleSelect.addEventListener('change', handleRole);
    facultySelect.addEventListener('change', function() {
        filterStudyPrograms(false);
    });

    filterStudyPrograms(true);
    handleRole();


    // Preview avatar
    document.getElementById('avatar-upload')
    ?.addEventListener('change', function(e){

        if (!e.target.files.length) {
            return;
        }

        const reader = new FileReader();

        reader.onload = function(event)
        {
            document.getElementById('avatar-preview')
                .src = event.target.result;
        };

        reader.readAsDataURL(e.target.files[0]);

    });


    // Show / hide password
    document.querySelectorAll('.toggle-password')
    .forEach(function(toggle){

        toggle.addEventListener('click', function(){

            const input =
                this.closest('.password-wrapper')
                    .querySelector('.password-input');

            input.type =
                input.type === 'password'
                ? 'text'
                : 'password';

        });

    });

    </script>

    @endpush
