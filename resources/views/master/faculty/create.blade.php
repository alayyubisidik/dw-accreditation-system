@extends('layout.app')

@section('content')

<div class="container-xl">

    <div class="card">

        <div class="card-header">

            <h3 class="card-title">
                Tambah Fakultas
            </h3>

            <div class="card-actions">

                <a href="{{ route('dashboard-admin.master.faculties.index') }}"
                   class="btn btn-primary">
                    Kembali
                </a>

            </div>

        </div>

        <div class="card-body">

            <form action="{{ route('dashboard-admin.master.faculties.store') }}"
                  method="POST">

                @csrf

                <div class="mb-3">

                    <label class="form-label required">
                        Nama Fakultas
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
