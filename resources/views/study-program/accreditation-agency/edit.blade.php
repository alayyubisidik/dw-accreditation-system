@extends('layout.app')

@section('content')

<div class="container-xl">

    <div class="card">

        <div class="card-header">

            <h3 class="card-title">
                Ubah Lembaga Akreditasi
            </h3>

            <div class="card-actions">

                <a href="{{ route('dashboard-admin.study-program.accreditation-agencies.index') }}"
                   class="btn btn-primary">
                    Kembali
                </a>

            </div>

        </div>

        <div class="card-body">

            <form action="{{ route('dashboard-admin.study-program.accreditation-agencies.update', $accreditationAgency) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="mb-3">

                    <label class="form-label required">
                        Nama Lembaga Akreditasi
                    </label>

                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name', $accreditationAgency->name) }}">

                    <x-input-error :messages="$errors->get('name')" />

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Deskripsi
                    </label>

                    <textarea name="description"
                              rows="4"
                              class="form-control">{{ old('description', $accreditationAgency->description) }}</textarea>

                    <x-input-error :messages="$errors->get('description')" />

                </div>

                <div class="card-footer text-end px-0 pb-0">

                    <button class="btn btn-primary">
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </div>

</div>

@endsection
