@extends('layout.app')

@section('content')
    <div class="container-xl">

        {{-- HEADER --}}
        <div class="card mb-3">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>

                        <h2 class="mb-1">
                            Input Data Indikator
                        </h2>

                        <div class="text-secondary">
                            {{ $accreditation->batch->name ?? '-' }}
                            &middot; {{ $accreditation->batch->agency->name ?? '-' }}
                            &middot; Tahun {{ $accreditation->batch->year ?? '-' }}
                        </div>

                    </div>

                    <a href="{{ route('dashboard-operator.accreditations.index') }}" class="btn btn-secondary">
                        Kembali
                    </a>

                </div>

            </div>

        </div>

        {{-- SUMMARY --}}
        <div class="row mb-3">

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="h1 mb-0">{{ $totalIndicators }}</div>
                        <div class="text-secondary">Total Indikator</div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="h1 mb-0 text-success">{{ $completedIndicators }}</div>
                        <div class="text-secondary">Sudah Diisi</div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="h1 mb-0 text-primary">{{ $completedTarget }}</div>
                        <div class="text-secondary">Target Tercapai</div>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card">
                    <div class="card-body text-center">
                        <div class="h1 mb-0 text-warning">{{ $unfinishedTarget }}</div>
                        <div class="text-secondary">Belum Capai Target</div>
                    </div>
                </div>
            </div>

        </div>

        {{-- PROGRESS --}}
        <div class="card mb-3">
            <div class="card-body">

                <div class="d-flex justify-content-between mb-2">
                    <span class="fw-bold">Progress Pengisian</span>
                    <span>{{ $progress }}%</span>
                </div>

                <div class="progress">
                    <div class="progress-bar" style="width: {{ $progress }}%"></div>
                </div>

            </div>
        </div>

        {{-- SEARCH & FILTER --}}
        <div class="card mb-3">
            <div class="card-body">

                <form method="GET">

                    <div class="row g-2">

                        <div class="col-md-6">
                            <input type="text" name="search" class="form-control" placeholder="Cari nama indikator..."
                                value="{{ request('search') }}">
                        </div>

                        <div class="col-md-3">
                            <select name="filter" class="form-select">

                                <option value="">Semua Data</option>

                                <option value="gap" {{ request('filter') == 'gap' ? 'selected' : '' }}>
                                    Prioritas Gap Terbesar
                                </option>

                                <option value="incomplete" {{ request('filter') == 'incomplete' ? 'selected' : '' }}>
                                    Belum Diisi
                                </option>

                                <option value="unfinished" {{ request('filter') == 'unfinished' ? 'selected' : '' }}>
                                    Belum Capai Target
                                </option>

                                <option value="achieved" {{ request('filter') == 'achieved' ? 'selected' : '' }}>
                                    Sudah Tercapai
                                </option>

                            </select>
                        </div>

                        <div class="col-md-2 d-grid">
                            <button class="btn btn-primary">
                                Cari
                            </button>
                        </div>

                        <div class="col-md-1 d-grid">
                            <a href="{{ route('dashboard-operator.accreditations.input.index', $accreditation) }}"
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
                <h3 class="card-title">Daftar Indikator</h3>
            </div>

            <div class="table-responsive">

                <table class="table table-vcenter card-table">

                    <thead>
                        <tr>
                            <th width="50">No</th>
                            <th>Indikator</th>
                            <th width="110">Jenis</th>
                            <th width="150">Capaian</th>
                            <th width="130">Target</th>
                            <th width="180">Status</th>
                            <th width="220">Kekurangan</th>
                            <th width="130">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                        @forelse($indicators as $indicator)
                            @php
                                $completed = $indicator->result !== null;
                                $achieved = $completed && $indicator->result >= $indicator->target;
                                $isPercentage = $indicator->formula_type === 'percentage';

                                // Percentage biasanya berbasis hitungan orang -> 0 desimal
                                // Ratio biasanya berbasis nilai kontinu (IPK, skor, dll) -> 2 desimal
                                $decimals = $isPercentage ? 0 : 2;
                            @endphp

                            <tr class="{{ !$completed ? 'table-warning' : '' }}">

                                {{-- NO --}}
                                <td>{{ $loop->iteration }}</td>

                                {{-- INDIKATOR --}}
                                <td>

                                    <div class="fw-semibold">
                                        {{ $indicator->title }}
                                    </div>

                                    <div class="small text-secondary">
                                        {{ $indicator->label_1 }}
                                        @if ($indicator->label_2)
                                            / {{ $indicator->label_2 }}
                                        @endif
                                    </div>

                                    @if ($indicator->description)
                                        <div class="small text-secondary mt-1">
                                            {{ \Illuminate\Support\Str::limit($indicator->description, 100) }}
                                        </div>
                                    @endif

                                </td>

                                {{-- JENIS --}}
                                <td>
                                    <span class="badge {{ $isPercentage ? 'bg-blue-lt' : 'bg-purple-lt' }}">
                                        {{ $isPercentage ? 'Persentase' : 'Rasio' }}
                                    </span>
                                </td>

                                {{-- CAPAIAN --}}
                                <td>

                                    @if ($completed)
                                        <div class="fw-bold">
                                            {{ number_format($indicator->result, 2) }}{{ $isPercentage ? '%' : '' }}
                                        </div>

                                        <div class="small text-secondary">
                                            {{ number_format($indicator->value_1, 0) }} /
                                            {{ number_format($indicator->value_2, 0) }}
                                        </div>
                                    @else
                                        <span class="text-secondary">-</span>
                                    @endif

                                </td>

                                {{-- TARGET --}}
                                <td>
                                    <span class="fw-bold">
                                        {{ number_format($indicator->target, 2) }}{{ $isPercentage ? '%' : '' }}
                                    </span>

                                    @if ($indicator->target_absolute !== null)
                                        <div class="small text-secondary">
                                            &asymp; {{ number_format($indicator->target_absolute, $decimals) }}
                                            {{ $indicator->gap_unit }}
                                        </div>
                                    @endif
                                </td>

                                {{-- STATUS --}}
                                <td>

                                    @if (!$completed)
                                        <span class="badge bg-secondary text-white">
                                            Belum Diisi
                                        </span>
                                    @elseif ($achieved)
                                        <span class="badge bg-success text-white">
                                            Tercapai ✓
                                        </span>
                                    @else
                                        <span class="badge bg-danger text-white">
                                            Belum Tercapai ✗
                                        </span>
                                    @endif

                                </td>

                                {{-- KEKURANGAN --}}
                                <td>

                                    @if (!$completed)
                                        <span class="text-secondary">
                                            Belum ada data
                                        </span>
                                    @elseif ($achieved)
                                        <span class="text-success fw-bold">
                                            Target sudah tercapai
                                        </span>
                                    @else
                                        <span class="text-danger fw-bold">
                                            Kurang {{ number_format($indicator->gap, $decimals) }}
                                            {{ $indicator->gap_unit ?? ($isPercentage ? '%' : '') }}
                                            lagi
                                        </span>
                                    @endif

                                </td>

                                {{-- AKSI --}}
                                <td>

                                    <a href="{{ route('dashboard-operator.accreditations.input.edit', [$accreditation, $indicator]) }}"
                                        class="btn btn-primary btn-sm">
                                        {{ $completed ? 'Ubah Data' : 'Isi Data' }}
                                    </a>

                                </td>

                            </tr>

                        @empty

                            <tr>
                                <td colspan="8" class="text-center py-5">
                                    <div class="text-secondary">
                                        Tidak ada indikator ditemukan
                                    </div>
                                </td>
                            </tr>
                        @endforelse

                    </tbody>

                </table>

            </div>

        </div>

    </div>
@endsection
