@extends('layout.app')

@section('content')

<div class="container-xl">

    <div class="card">

        <div class="card-header">

            <h3 class="card-title">
                Tambah Program Studi
            </h3>

            <div class="card-actions">
                <a href="{{ route('dashboard-admin.master.study-programs.index') }}"
                   class="btn btn-primary">
                    Kembali
                </a>
            </div>

        </div>

        <div class="card-body">

            <form action="{{ route('dashboard-admin.master.study-programs.store') }}"
                  method="POST">

                @csrf

                <div class="mb-3">

                    <label class="form-label required">
                        Fakultas
                    </label>

                    <select name="faculty_id"
                            class="form-control">

                        <option value="">
                            Pilih Fakultas
                        </option>

                        @foreach($faculties as $faculty)

                            <option value="{{ $faculty->id }}"
                                {{ old('faculty_id') == $faculty->id ? 'selected' : '' }}>

                                {{ $faculty->name }}

                            </option>

                        @endforeach

                    </select>

                    <x-input-error :messages="$errors->get('faculty_id')" />

                </div>

                <div class="mb-3">

                    <label class="form-label required">
                        Nama Program Studi
                    </label>

                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name') }}">

                    <x-input-error :messages="$errors->get('name')" />

                </div>

                <div class="card-footer text-end px-0 pb-0">

                    <button class="btn btn-primary">
                        Simpan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
