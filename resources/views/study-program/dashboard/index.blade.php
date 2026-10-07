@extends('layout.app')

@section('content')
    <div class="container-xl py-4">

        {{-- ══════════════════════════════════════════════════════════════
             PAGE TITLE
        ══════════════════════════════════════════════════════════════ --}}
        <div class="page-header mb-4">
            <div class="row align-items-center">
                <div class="col">
                    <div class="page-pretitle text-secondary">Admin</div>
                    <h1 class="page-title fw-bold">
                        <i class="ti ti-school me-2 text-primary"></i>
                        Dashboard Program Studi
                    </h1>
                    <p class="text-secondary mt-1 mb-0">
                        Ringkasan dan monitoring akreditasi seluruh program studi
                    </p>
                </div>
                
            </div>
        </div>



        {{-- ══════════════════════════════════════════════════════════════
             STAT CARDS ROW 1
        ══════════════════════════════════════════════════════════════ --}}
        <div class="row g-3 mb-4">

            {{-- Program Studi --}}
            <div class="col-sm-6 col-xl-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-3 rounded-3" style="background:#eff6ff;">
                                <i class="ti ti-briefcase text-primary" style="font-size:1.8rem;"></i>
                            </div>
                            <div>
                                <div class="text-secondary small">Program Studi</div>
                                <div class="h2 mb-0 fw-bold">{{ $totalStudyPrograms }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0 pt-0">
                        <a href="{{ route('dashboard-admin.master.study-programs.index') }}"
                            class="text-primary small fw-semibold text-decoration-none">
                            Kelola Program Studi <i class="ti ti-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Lembaga Akreditasi --}}
            <div class="col-sm-6 col-xl-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-3 rounded-3" style="background:#fdf4ff;">
                                <i class="ti ti-building-bank" style="font-size:1.8rem;color:#a855f7;"></i>
                            </div>
                            <div>
                                <div class="text-secondary small">Lembaga Akreditasi</div>
                                <div class="h2 mb-0 fw-bold">{{ $totalAgencies }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0 pt-0">
                        <a href="{{ route('dashboard-admin.study-program.accreditation-agencies.index') }}"
                            class="small fw-semibold text-decoration-none" style="color:#a855f7;">
                            Kelola Lembaga <i class="ti ti-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Batch Akreditasi --}}
            <div class="col-sm-6 col-xl-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-3 rounded-3" style="background:#fff7ed;">
                                <i class="ti ti-calendar-event" style="font-size:1.8rem;color:#f97316;"></i>
                            </div>
                            <div>
                                <div class="text-secondary small">Total Batch</div>
                                <div class="h2 mb-0 fw-bold">{{ $totalBatches }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0 pt-0">
                        <a href="{{ route('dashboard-admin.study-program.accreditation-batches.index') }}"
                            class="small fw-semibold text-decoration-none" style="color:#f97316;">
                            Kelola Batch <i class="ti ti-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

            {{-- Total Akreditasi --}}
            <div class="col-sm-6 col-xl-3">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-3 rounded-3" style="background:#f0fdf4;">
                                <i class="ti ti-medal" style="font-size:1.8rem;color:#22c55e;"></i>
                            </div>
                            <div>
                                <div class="text-secondary small">Total Akreditasi</div>
                                <div class="h2 mb-0 fw-bold">{{ $totalAccreditations }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0 pt-0">
                        <a href="{{ route('dashboard-admin.study-program.accreditations.index') }}"
                            class="small fw-semibold text-decoration-none" style="color:#22c55e;">
                            Lihat Semua <i class="ti ti-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </div>

        </div>


        {{-- ══════════════════════════════════════════════════════════════
             PROGRESS PER PRODI — SEMUA BATCH
        ══════════════════════════════════════════════════════════════ --}}
        <div class="mb-2">
            <h3 class="fw-bold mb-0">
                <i class="ti ti-list-check me-2 text-primary"></i>
                Progress per Program Studi
            </h3>
            <p class="text-secondary mt-1 mb-0">Menampilkan progres pengisian indikator untuk setiap batch akreditasi.</p>
        </div>

        @forelse ($allBatchesProgress as $batch)
            <div class="card shadow-sm border-0 mb-4">
                {{-- Card header --}}
                <div class="card-header border-bottom d-flex align-items-center gap-3 flex-wrap py-3">
                    <div class="p-2 rounded-2" style="background:{{ $batch->is_active ? '#eff6ff' : '#f8fafc' }};">
                        <i class="ti ti-calendar-event"
                            style="font-size:1.4rem; color:{{ $batch->is_active ? '#3b82f6' : '#94a3b8' }};"></i>
                    </div>
                    <div class="flex-fill">
                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <h4 class="card-title mb-0 fw-bold">{{ $batch->name }}</h4>
                            @if ($batch->is_active)
                                <span class="badge bg-primary">Aktif</span>
                            @else
                                <span class="badge bg-secondary">Nonaktif</span>
                            @endif
                        </div>
                        <div class="small text-secondary mt-1">
                            <i class="ti ti-building-bank me-1"></i>{{ $batch->agency->name ?? '-' }}
                            &nbsp;&middot;&nbsp;
                            <i class="ti ti-calendar me-1"></i>Tahun {{ $batch->year ?? '-' }}
                            &nbsp;&middot;&nbsp;
                            <i class="ti ti-school me-1"></i>{{ $batch->accreditationProgress->count() }} program studi
                        </div>
                    </div>

                </div>

                {{-- Table --}}
                @if ($batch->accreditationProgress->isEmpty())
                    <div class="card-body">
                        <div class="empty py-4">
                            <div class="empty-img">
                                <i class="ti ti-mood-empty" style="font-size:2.6rem; color:#cbd5e1;"></i>
                            </div>
                            <p class="empty-title">Belum ada program studi terdaftar</p>
                            <p class="empty-subtitle text-secondary">Tambahkan akreditasi program studi pada batch ini.</p>
                        </div>
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-vcenter card-table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width:1%;" class="text-center">#</th>
                                    <th>Program Studi</th>
                                    <th style="min-width:180px;">Progress Pengisian</th>
                                    <th class="text-center">Total Indikator</th>
                                    <th class="text-center">Terisi</th>
                                    <th class="text-center">Tercapai</th>
                                    <th class="text-center">Belum Tercapai</th>
                                    <th class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($batch->accreditationProgress as $i => $acc)
                                    @php
                                        $barColor = $acc->progress >= 80 ? 'bg-success' : ($acc->progress >= 40 ? 'bg-primary' : 'bg-warning');
                                        $textColor = $acc->progress >= 80 ? 'text-success' : ($acc->progress >= 40 ? 'text-primary' : 'text-warning');
                                    @endphp
                                    <tr>
                                        <td class="text-center text-secondary">{{ $loop->iteration }}</td>
                                        <td class="fw-medium">{{ $acc->studyProgram->name ?? '-' }}</td>
                                        <td>
                                            <div class="d-flex align-items-center gap-2">
                                                <div class="progress flex-fill" style="height:8px; border-radius:6px;">
                                                    <div class="progress-bar {{ $barColor }}"
                                                        style="width:{{ $acc->progress }}%; border-radius:6px;"
                                                        aria-valuenow="{{ $acc->progress }}" aria-valuemin="0" aria-valuemax="100">
                                                    </div>
                                                </div>
                                                <span class="small fw-bold {{ $textColor }}" style="width:40px;">{{ $acc->progress }}%</span>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $acc->total_indicators }}</td>
                                        <td class="text-center fw-semibold text-primary">{{ $acc->filled_indicators }}</td>
                                        <td class="text-center fw-semibold text-success">{{ $acc->achieved_indicators }}</td>
                                        <td class="text-center fw-semibold text-danger">{{ $acc->filled_indicators - $acc->achieved_indicators }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('dashboard-admin.study-program.accreditations.input.index', $acc) }}"
                                                class="btn btn-sm btn-outline-primary">
                                                <i class="ti ti-pencil me-1"></i>Input
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        @empty
            <div class="card shadow-sm border-0">
                <div class="card-body">
                    <div class="empty py-5">
                        <div class="empty-img">
                            <i class="ti ti-calendar-off" style="font-size:3rem; color:#cbd5e1;"></i>
                        </div>
                        <p class="empty-title">Belum ada batch akreditasi</p>
                        <p class="empty-subtitle text-secondary">Buat batch akreditasi terlebih dahulu.</p>
                        <div class="empty-action">
                            <a href="{{ route('dashboard-admin.study-program.accreditation-batches.index') }}"
                                class="btn btn-primary">
                                <i class="ti ti-plus me-1"></i>Buat Batch
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endforelse

    </div>
@endsection
