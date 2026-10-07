@extends('layout.app')

@section('content')

<div class="container-xl">

    <div class="card">

        <div class="card-header">

            <h3 class="card-title">
                Ubah Akreditasi
            </h3>

            <div class="card-actions">

                <a href="{{ route('dashboard-operator.accreditations.index') }}"
                   class="btn btn-primary">
                    Kembali
                </a>

            </div>

        </div>

        <div class="card-body">

            <form action="{{ route('dashboard-operator.accreditations.update', $accreditation) }}"
                  method="POST">

                @csrf
                @method('PUT')

                <div class="mb-3">

                    <label class="form-label required">
                        Nama Akreditasi
                    </label>

                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name', $accreditation->name) }}">

                    <x-input-error :messages="$errors->get('name')" />

                </div>

                <div class="mb-3">

                    <label class="form-label required">
                        Tahun
                    </label>

                    <select name="year"
                            class="form-select">

                        <option value="">
                            Pilih Tahun
                        </option>

                        @for ($year = 2026; $year <= 2035; $year++)
                            <option value="{{ $year }}"
                                @selected(old('year', $accreditation->year) == $year)>
                                {{ $year }}
                            </option>
                        @endfor

                    </select>

                    <x-input-error :messages="$errors->get('year')" />

                </div>

                <div class="mb-3">

                    <label class="form-label">
                        Deskripsi
                    </label>

                    <textarea name="description"
                              rows="4"
                              class="form-control">{{ old('description', $accreditation->description) }}</textarea>

                    <x-input-error :messages="$errors->get('description')" />

                </div>

                <div class="mb-3">

                    <label class="form-label required">
                        Status
                    </label>

                    <select name="status"
                            class="form-select">

                        <option value="unfinished"
                            @selected(old('status', $accreditation->status) == 'unfinished')>
                            Belum Selesai
                        </option>

                        <option value="completed"
                            @selected(old('status', $accreditation->status) == 'completed')>
                            Selesai
                        </option>

                    </select>

                    <x-input-error :messages="$errors->get('status')" />

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
