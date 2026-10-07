@extends('layout.app')

@section('content')
    <div class="container-xl">

        <div class="card">

            {{-- HEADER --}}
            <div class="card-header">

                <div>
                    <h3 class="card-title">
                        Edit Batch Akreditasi
                    </h3>

                    <div class="text-secondary">
                        Perbarui informasi batch akreditasi program studi
                    </div>
                </div>

                <div class="card-actions">
                    <a href="{{ route('dashboard-admin.study-program.accreditation-batches.index') }}"
                        class="btn btn-secondary">
                        Kembali
                    </a>
                </div>
            </div>

            {{-- FORM --}}
            <form action="{{ route('dashboard-admin.study-program.accreditation-batches.update', $accreditationBatch) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="card-body">

                    {{-- ACCREDITATION AGENCY --}}
                    <div class="mb-3">

                        <label class="form-label required">
                            Lembaga Akreditasi
                        </label>

                        <select name="study_program_accreditation_agency_id" class="form-select">

                            <option value="">-- Pilih Lembaga Akreditasi --</option>

                            @foreach ($agencies as $agency)
                                <option value="{{ $agency->id }}"
                                    {{ old('study_program_accreditation_agency_id', $accreditationBatch->study_program_accreditation_agency_id) == $agency->id ? 'selected' : '' }}>
                                    {{ $agency->name }}
                                </option>
                            @endforeach

                        </select>

                        <x-input-error :messages="$errors->get('study_program_accreditation_agency_id')" />

                    </div>

                    {{-- NAME --}}
                    <div class="mb-3">

                        <label class="form-label required">
                            Nama Batch
                        </label>

                        <input type="text" name="name" class="form-control"
                            value="{{ old('name', $accreditationBatch->name) }}"
                            placeholder="Contoh: Akreditasi Program Studi 2026">

                        <x-input-error :messages="$errors->get('name')" />

                    </div>

                    {{-- YEAR --}}
                    <div class="mb-3">

                        <label class="form-label required">
                            Tahun
                        </label>

                        <input type="number" name="year" class="form-control"
                            value="{{ old('year', $accreditationBatch->year) }}"
                            min="2000" max="2100">

                        <x-input-error :messages="$errors->get('year')" />

                    </div>

                    {{-- IS ACTIVE --}}
                    <div class="mb-3">

                        <label class="form-check form-switch">

                            <input type="checkbox" name="is_active" class="form-check-input" value="1"
                                {{ old('is_active', $accreditationBatch->is_active) ? 'checked' : '' }}>

                            <span class="form-check-label">
                                Aktifkan batch ini
                            </span>

                        </label>

                        <div class="form-hint">
                            Batch yang aktif akan digunakan sebagai periode berjalan untuk pengisian akreditasi.
                        </div>

                        <x-input-error :messages="$errors->get('is_active')" />

                    </div>

                    {{-- DESCRIPTION --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Deskripsi
                        </label>

                        <textarea name="description" rows="3" class="form-control">{{ old('description', $accreditationBatch->description) }}</textarea>

                        <x-input-error :messages="$errors->get('description')" />

                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="card-footer text-end">

                    <button type="submit" class="btn btn-primary">
                        Simpan Perubahan
                    </button>

                </div>

            </form>

        </div>

    </div>
@endsection
