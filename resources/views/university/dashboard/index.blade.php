@extends('layout.app')

@section('content')
    <div class="container-xl py-4">
        <div class="page-header mb-4">
            <div class="row align-items-center g-3">
                <div class="col">
                    <div class="page-pretitle text-secondary mb-1">Admin</div>
                    <h1 class="page-title fw-bold mb-1">
                        <i class="ti ti-building me-2 text-primary"></i>
                        Dashboard Universitas
                    </h1>
                    <p class="text-secondary mb-0">Ringkasan dan monitoring akreditasi universitas</p>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-4">
            <div class="col-sm-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-3 rounded-3 flex-shrink-0" style="background:#fdf4ff;">
                                <i class="ti ti-building-bank" style="font-size:1.8rem;color:#a855f7;"></i>
                            </div>
                            <div>
                                <div class="text-secondary small mb-1">Lembaga Akreditasi</div>
                                <div class="h2 mb-0 fw-bold">{{ $totalAgencies }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0 px-4 pt-0 pb-4">
                        <a href="{{ route('dashboard-admin.university.accreditation-agencies.index') }}"
                            class="small fw-semibold text-decoration-none" style="color:#a855f7;">
                            Kelola Lembaga <i class="ti ti-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-sm-6">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center gap-3">
                            <div class="p-3 rounded-3 flex-shrink-0" style="background:#f0fdf4;">
                                <i class="ti ti-medal" style="font-size:1.8rem;color:#22c55e;"></i>
                            </div>
                            <div>
                                <div class="text-secondary small mb-1">Total Akreditasi</div>
                                <div class="h2 mb-0 fw-bold">{{ $totalAccreditations }}</div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent border-top-0 px-4 pt-0 pb-4">
                        <a href="{{ route('dashboard-admin.university.accreditations.index') }}"
                            class="small fw-semibold text-decoration-none" style="color:#22c55e;">
                            Lihat Semua <i class="ti ti-arrow-right ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="mb-3">
            <h3 class="fw-bold mb-1">
                <i class="ti ti-list-check me-2 text-primary"></i>
                Progress Akreditasi Universitas
            </h3>
            <p class="text-secondary mb-0">Menampilkan progres pengisian indikator untuk setiap akreditasi.</p>
        </div>

        <div class="card shadow-sm border-0">
            <div class="table-responsive">
                <table class="table table-vcenter card-table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Akreditasi</th>
                            <th>Lembaga</th>
                            <th>Tahun</th>
                            <th>Status</th>
                            <th>Indikator</th>
                            <th style="min-width:180px;">Progress Pengisian</th>
                            <th style="min-width:180px;">Progress Tercapai</th>
                            <th class="text-center pe-4">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($accreditationProgress as $accreditation)
                            <tr>
                                <td class="fw-semibold ps-4">{{ $accreditation->name ?? 'Akreditasi Universitas' }}</td>
                                <td>{{ $accreditation->agency->name ?? '-' }}</td>
                                <td>{{ $accreditation->year ?? '-' }}</td>
                                <td>
                                    <span class="badge {{ $accreditation->status === 'completed' ? 'bg-success' : 'bg-warning' }} text-white">
                                        {{ $accreditation->status === 'completed' ? 'Selesai' : 'Sedang Berjalan' }}
                                    </span>
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $accreditation->total_indicators }} indikator</div>
                                    <div class="small text-secondary mt-1">
                                        Terisi: {{ $accreditation->filled_indicators }} &middot; Tercapai: {{ $accreditation->achieved_indicators }}
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-fill" style="height:8px; border-radius:6px;">
                                            <div class="progress-bar bg-primary"
                                                style="width:{{ $accreditation->progress }}%; border-radius:6px;"
                                                aria-valuenow="{{ $accreditation->progress }}" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                        <span class="small fw-bold text-primary" style="width:40px;">{{ $accreditation->progress }}%</span>
                                    </div>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-fill" style="height:8px; border-radius:6px;">
                                            <div class="progress-bar bg-success"
                                                style="width:{{ $accreditation->achieved_progress }}%; border-radius:6px;"
                                                aria-valuenow="{{ $accreditation->achieved_progress }}" aria-valuemin="0" aria-valuemax="100">
                                            </div>
                                        </div>
                                        <span class="small fw-bold text-success" style="width:40px;">{{ $accreditation->achieved_progress }}%</span>
                                    </div>
                                </td>
                                <td class="text-center pe-4">
                                    <a href="{{ route('dashboard-admin.university.accreditations.input.index', $accreditation) }}"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="ti ti-pencil me-1"></i>Input
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8">
                                    <div class="empty py-5 px-4">
                                        <div class="empty-img">
                                            <i class="ti ti-building-off" style="font-size:3rem; color:#cbd5e1;"></i>
                                        </div>
                                        <p class="empty-title">Belum ada akreditasi</p>
                                        <p class="empty-subtitle text-secondary">Buat akreditasi terlebih dahulu.</p>
                                        <div class="empty-action">
                                            <a href="{{ route('dashboard-admin.university.accreditations.create') }}"
                                                class="btn btn-primary">
                                                <i class="ti ti-plus me-1"></i>Buat Akreditasi
                                            </a>
                                        </div>
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
