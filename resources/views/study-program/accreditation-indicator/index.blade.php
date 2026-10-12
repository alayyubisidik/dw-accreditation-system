@extends('layout.app')

@section('content')
    <div class="container-xl">

        {{-- HEADER --}}
        <div class="card mb-3">
            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <h2 class="mb-1">
                            {{ $accreditation->batch->name ?? '-' }}
                        </h2>

                        <div class="text-secondary">
                            {{ $accreditation->batch->agency->name ?? '-' }}
                            &middot; Tahun {{ $accreditation->batch->year ?? '-' }}
                        </div>
                    </div>

                    <div class="d-flex gap-2">

                        <a href="{{ route('dashboard-admin.study-program.accreditations.input.index', $accreditation) }}"
                            class="btn btn-success">
                            Input Nilai
                        </a>

                        <a href="{{ route('dashboard-admin.study-program.accreditations.indicators.create', $accreditation) }}"
                            class="btn btn-primary">
                            Tambah Indikator
                        </a>

                        <a href="{{ route('dashboard-admin.study-program.accreditations.index') }}" class="btn btn-secondary">
                            Kembali
                        </a>

                    </div>

                </div>

            </div>
        </div>

        {{-- FILTER --}}
        <div class="card mb-3">

            <div class="card-body">

                <form method="GET">

                    <div class="row g-2">

                        <div class="col-md-9">
                            <input type="text" name="search" class="form-control" placeholder="Cari nama indikator..."
                                value="{{ request('search') }}">
                        </div>

                        <div class="col-md-2 d-grid">
                            <button type="submit" class="btn btn-primary">
                                Cari
                            </button>
                        </div>

                        <div class="col-md-1 d-grid">
                            <a href="{{ route('dashboard-admin.study-program.accreditations.indicators.index', $accreditation) }}"
                                class="btn btn-secondary">
                                Reset
                            </a>
                        </div>

                    </div>

                </form>

            </div>

        </div>

        {{-- TABLE --}}
        <div class="card">

            <div class="card-header">

                <h3 class="card-title">
                    Daftar Indikator
                </h3>

                <div class="card-actions d-flex gap-2">

                    <form action="{{ route('dashboard-admin.study-program.accreditations.indicators.import', $accreditation) }}"
                        method="POST" class="d-flex gap-2">

                        @csrf

                        <select name="indicator_template_id" class="form-select" style="min-width:320px">
                            <option value="">Pilih indikator master...</option>
                            @foreach ($availableTemplates as $template)
                                <option value="{{ $template->id }}">
                                    {{ $loop->iteration }}. {{ $template->title }}
                                </option>
                            @endforeach
                        </select>

                        <button class="btn btn-primary" @disabled($availableTemplates->isEmpty())>
                            Ambil
                        </button>

                    </form>

                    <form action="{{ route('dashboard-admin.study-program.accreditations.indicators.import', $accreditation) }}"
                        method="POST">

                        @csrf

                        <input type="hidden" name="take_all" value="1">

                        <button class="btn btn-outline-primary" @disabled($availableTemplates->isEmpty())>
                            Ambil Semua
                        </button>

                    </form>

                </div>

            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-vcenter card-table">

                        <thead>
                            <tr>
                                <th width="60">No</th>
                                <th>Indikator</th>
                                <th width="160">Formula</th>
                                <th width="140">Target</th>
                                <th width="80">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($indicators as $indicator)
                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>

                                        <div class="fw-bold">
                                            {{ $indicator->title }}
                                        </div>

                                        @if ($indicator->description)
                                            <div class="small text-secondary mt-1">
                                                {{ \Illuminate\Support\Str::limit($indicator->description, 100) }}
                                            </div>
                                        @endif

                                    </td>

                                    <td>

                                        @if ($indicator->formula_type === 'percentage')
                                            <span class="badge bg-green-lt">
                                                Persentase
                                            </span>
                                        @elseif ($indicator->formula_type === 'ratio')
                                            <span class="badge bg-blue-lt">
                                                Rasio
                                            </span>
                                        @endif

                                    </td>

                                    <td>

                                        @if ($indicator->formula_type === 'percentage')
                                            {{ number_format($indicator->target, 2) }}%
                                        @elseif ($indicator->formula_type === 'ratio')
                                            {{ number_format($indicator->target, 2) }}
                                        @endif

                                    </td>

                                    <td>

                                        <form
                                            action="{{ route('dashboard-admin.study-program.accreditations.indicators.destroy', [$accreditation, $indicator]) }}"
                                            method="POST">

                                            @csrf
                                            @method('DELETE')

                                            <button type="submit" class="btn btn-link text-danger p-0 delete-btn">
                                                <i class="ti ti-trash"></i>
                                            </button>

                                        </form>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="5" class="text-center py-5 text-secondary">
                                        Tidak ada indikator ditemukan
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        </div>

    </div>
@endsection
