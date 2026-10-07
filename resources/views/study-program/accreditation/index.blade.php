@extends('layout.app')

@section('content')
    <div class="container-xl">

        <div class="card" style="border-radius:7px;">

            {{-- HEADER --}}
            <div class="card-header">
                <h3 class="card-title">
                    Akreditasi Program Studi
                </h3>
            </div>

            <div class="card-body border-bottom">

                {{-- FILTER --}}
                <form action="{{ route('dashboard-admin.study-program.accreditations.index') }}" method="GET"
                    class="d-flex flex-wrap gap-2">

                    {{-- SEARCH --}}
                    <input type="text" name="search" class="form-control"
                        placeholder="Cari batch / lembaga akreditasi..." value="{{ request('search') }}"
                        style="width:280px">

                    {{-- PROGRAM STUDI --}}
                    <select name="study_program_id" class="form-select" style="width:240px">

                        <option value="">Semua Program Studi</option>

                        @foreach ($studyPrograms as $studyProgram)
                            <option value="{{ $studyProgram->id }}" @selected(request('study_program_id') == $studyProgram->id)>
                                {{ $studyProgram->name }}
                            </option>
                        @endforeach

                    </select>

                    {{-- TAHUN --}}
                    <select name="year" class="form-select" style="width:170px">
                        <option value="">Semua Tahun</option>
                        @foreach ($years as $year)
                            <option value="{{ $year }}" @selected(request('year') == $year)>
                                {{ $year }}
                            </option>
                        @endforeach
                    </select>

                    {{-- STATUS --}}
                    <select name="status" class="form-select" style="width:180px">
                        <option value="">Semua Status</option>
                        <option value="unfinished" @selected(request('status') === 'unfinished')>
                            Belum Selesai
                        </option>
                        <option value="completed" @selected(request('status') === 'completed')>
                            Selesai
                        </option>
                    </select>

                    <button class="btn btn-primary">
                        <i class="ti ti-search"></i>
                    </button>

                    @if (request()->filled('search') ||
                            request()->filled('year') ||
                            request()->filled('study_program_id') ||
                            request()->filled('status'))
                        <a href="{{ route('dashboard-admin.study-program.accreditations.index') }}" class="btn btn-secondary">
                            Reset
                        </a>
                    @endif

                </form>

            </div>

            {{-- BODY --}}
            <div class="card-body">

                <div class="row">

                    @forelse($accreditations as $accreditation)
                        @php
                            $batch = $accreditation->batch;
                            $agency = $batch?->agency;

                            $totalIndicators = $accreditation->total_indicators;
                            $completedIndicators = $accreditation->completed_indicators;
                            $achievedIndicators = $accreditation->achieved_indicators;

                            // Progress = indikator yang sudah DIISI
                            $progress =
                                $totalIndicators > 0 ? round(($completedIndicators / $totalIndicators) * 100) : 0;

                            $statusBadge = match ($accreditation->status) {
                                'completed' => ['bg-success', 'Selesai'],
                                'in_progress' => ['bg-warning', 'Belum Selesai'],
                            };
                        @endphp

                        <div class="col-lg-4 col-md-6 mb-4">

                            <div class="card shadow-sm h-100">

                                <div class="card-body">

                                    {{-- HEADER --}}
                                    <div class="d-flex justify-content-between align-items-start mb-3">

                                        <div>
                                            <h3 class="mb-1">
                                                {{ $batch->name ?? '-' }}
                                            </h3>
                                            <div class="text-secondary small mb-1">
                                                {{ $accreditation->studyProgram->name }}
                                            </div>
                                            <div class="text-secondary">
                                                {{ $agency->name ?? '-' }} &middot; Tahun {{ $batch->year ?? '-' }}
                                            </div>
                                        </div>

                                        <span class="badge {{ $statusBadge[0] }} text-white">
                                            {{ $statusBadge[1] }}
                                        </span>

                                    </div>

                                  
                                    {{-- PROGRESS TERCAPAI TARGET --}}
                                    <div class="mb-4">
                                        <div class="d-flex justify-content-between mb-1">
                                            <span class="text-secondary">
                                                Progress Pengisian
                                            </span>
                                            <strong>{{ $progress }}%</strong>
                                        </div>

                                        <div class="progress">
                                            <div class="progress-bar bg-primary" role="progressbar"
                                                style="width: {{ $progress }}%" aria-valuenow="{{ $progress }}"
                                                aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                    </div>

                                    {{-- STATISTIK --}}
                                    <div class="row text-center g-3">

                                        <div class="col-4">
                                            <div class="h1 mb-0">{{ $totalIndicators }}</div>
                                            <div class="text-secondary">Indikator</div>
                                        </div>

                                        <div class="col-4">
                                            <div class="h1 mb-0 text-primary">{{ $completedIndicators }}</div>
                                            <div class="text-secondary">Terisi</div>
                                        </div>

                                        <div class="col-4">
                                            <div class="h1 mb-0 text-warning">
                                                {{ $totalIndicators - $completedIndicators }}
                                            </div>
                                            <div class="text-secondary">Belum Terisi</div>
                                        </div>

                                        <div class="col-6 mt-3">
                                            <div class="h1 mb-0 text-success">{{ $achievedIndicators }}</div>
                                            <div class="text-secondary">Tercapai</div>
                                        </div>

                                        <div class="col-6 mt-3">
                                            <div class="h1 mb-0 text-danger">
                                                {{ $completedIndicators - $achievedIndicators }}
                                            </div>
                                            <div class="text-secondary">Belum Tercapai</div>
                                        </div>

                                    </div>

                                </div>

                                <div class="card-footer bg-transparent">

                                    <div class="d-grid gap-2">

                                        <a href="{{ route('dashboard-admin.study-program.accreditations.input.index', $accreditation) }}"
                                            class="btn btn-primary">
                                            Input Data
                                        </a>

                                        <a href="{{ route('dashboard-admin.study-program.accreditations.indicators.index', $accreditation) }}"
                                            class="btn btn-outline-primary">
                                            Daftar Indikator
                                        </a>

                                        <a href="{{ route('dashboard-admin.study-program.accreditations.preview', $accreditation) }}"
                                            class="btn btn-success" target="_blank">
                                            Preview Dokumen
                                        </a>

                                    </div>

                                </div>

                            </div>

                        </div>

                    @empty

                        <div class="col-12">
                            <div class="empty">
                                <div class="empty-header">0</div>
                                <p class="empty-title">Data tidak ditemukan</p>
                                <p class="empty-subtitle text-secondary">
                                    Belum ada Batch Akreditasi yang mencakup Program Studi Anda, atau ubah filter
                                    pencarian.
                                </p>
                            </div>
                        </div>
                    @endforelse

                </div>

            </div>

            {{-- FOOTER --}}
            <div class="card-footer">
                Total Akreditasi :
                <strong>{{ $accreditations->count() }}</strong>
            </div>

        </div>

    </div>
@endsection
