@extends('layout.app')

@section('content')
    <div class="container-xl">
        <div class="card">
            <div class="card-header">
                <div>
                    <h3 class="card-title">Edit Akreditasi Universitas</h3>
                    <div class="text-secondary">Perbarui data akreditasi universitas.</div>
                </div>
                <div class="card-actions">
                    <a href="{{ route('dashboard-admin.university.accreditations.index') }}" class="btn btn-secondary">Kembali</a>
                </div>
            </div>

            <form action="{{ route('dashboard-admin.university.accreditations.update', $accreditation) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label required">Lembaga Akreditasi</label>
                        <select name="university_accreditation_agency_id" class="form-select">
                            <option value="">-- Pilih Lembaga Akreditasi --</option>
                            @foreach ($agencies as $agency)
                                <option value="{{ $agency->id }}" @selected(old('university_accreditation_agency_id', $accreditation->university_accreditation_agency_id) == $agency->id)>
                                    {{ $agency->name }}
                                </option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('university_accreditation_agency_id')" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama</label>
                        <input type="text" name="name" class="form-control" value="{{ old('name', $accreditation->name) }}" placeholder="Contoh: Akreditasi Universitas 2026">
                        <x-input-error :messages="$errors->get('name')" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label required">Tahun</label>
                        <input type="number" name="year" class="form-control" value="{{ old('year', $accreditation->year) }}" min="2000" max="2100">
                        <x-input-error :messages="$errors->get('year')" />
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Deskripsi</label>
                        <textarea name="description" rows="3" class="form-control">{{ old('description', $accreditation->description) }}</textarea>
                        <x-input-error :messages="$errors->get('description')" />
                    </div>
                </div>

                <div class="card-footer text-end">
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
@endsection
