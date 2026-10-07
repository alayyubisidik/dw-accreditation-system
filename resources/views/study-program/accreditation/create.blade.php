@extends('layout.app')

@section('content')

<div class="container-xl">

    <div class="card">

        <div class="card-header">

            <h3 class="card-title">
                Tambah Akreditasi
            </h3>

            <div class="card-actions">

                <a href="{{ route('dashboard-admin.study-program.accreditations.index') }}"
                   class="btn btn-primary">
                    Kembali
                </a>

            </div>

        </div>

        <div class="card-body">

            <form action="{{ route('dashboard-admin.study-program.accreditations.store') }}"
                  method="POST">

                @csrf

                <div class="mb-3">

                    <label class="form-label required">
                        Nama Akreditasi
                    </label>

                    <input type="text"
                           name="name"
                           class="form-control"
                           value="{{ old('name') }}">

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
                                @selected(old('year') == $year)>
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
                              class="form-control">{{ old('description') }}</textarea>

                    <x-input-error :messages="$errors->get('description')" />

                </div>

                <div class="mb-3">

                    <label class="form-label required">
                        Status
                    </label>

                    <select name="status"
                            class="form-select">

                        <option value="unfinished"
                            @selected(old('status', 'unfinished') == 'unfinished')>
                            Belum Selesai
                        </option>

                        <option value="completed"
                            @selected(old('status') == 'completed')>
                            Selesai
                        </option>

                    </select>

                    <x-input-error :messages="$errors->get('status')" />

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
