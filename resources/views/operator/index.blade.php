@extends('layout.app')

@section('content')

    <div class="container-xl">

        {{-- HEADER --}}
        <div class="mb-4">

            <h2 class="page-title mb-2">
                Dashboard Operator
            </h2>

            <div class="text-secondary">
                @if ($studyProgram)
                    {{ $studyProgram->name }} &middot; {{ $studyProgram->faculty->name ?? '-' }}
                @else
                    Anda belum terhubung dengan Program Studi manapun. Hubungi admin.
                @endif
            </div>

        </div>

        @if ($studyProgram)

            {{-- STAT CARDS --}}
            <div class="row row-cards mb-4">

                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="h1 mb-1">{{ $totalIndicators }}</div>
                            <div class="text-secondary">Total Indikator</div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="h1 mb-1 text-success">{{ $achievedIndicators }}</div>
                            <div class="text-secondary">Tercapai</div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="h1 mb-1 text-danger">{{ $unachievedIndicators }}</div>
                            <div class="text-secondary">Belum Tercapai</div>
                        </div>
                    </div>
                </div>

                <div class="col-sm-6 col-lg-3">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="h1 mb-1 text-warning">{{ $incompleteIndicators }}</div>
                            <div class="text-secondary">Belum Diisi</div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="row row-deck row-cards mb-4">

                {{-- DAFTAR AKREDITASI PRODI INI --}}
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Akreditasi Program Studi Anda</h3>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-vcenter card-table">
                                    <thead>
                                        <tr>
                                            <th>Batch</th>
                                            <th>Lembaga</th>
                                            <th style="width: 160px">Progress</th>
                                            <th>Status</th>
                                            <th style="width: 160px">Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse ($accreditations as $accreditation)
                                            @php
                                                $batch = $accreditation->batch;
                                                $statusBadge = match ($accreditation->status) {
                                                    'completed' => ['bg-success', 'Selesai'],
                                                    'in_progress' => ['bg-warning', 'Belum Selesai'],
                                                };
                                            @endphp
                                            <tr>
                                                <td>
                                                    {{ $batch->name ?? '-' }}
                                                    <div class="text-secondary small">Tahun {{ $batch->year ?? '-' }}</div>
                                                </td>
                                                <td>{{ $batch->agency->name ?? '-' }}</td>
                                                <td>
                                                    <div class="d-flex align-items-center gap-2">
                                                        <div class="progress flex-grow-1" style="height: 6px;">
                                                            <div class="progress-bar bg-primary" style="width: {{ $accreditation->progress }}%"></div>
                                                        </div>
                                                        <span class="text-secondary small">{{ $accreditation->progress }}%</span>
                                                    </div>
                                                </td>
                                                <td>
                                                    <span class="badge {{ $statusBadge[0] }} text-white">{{ $statusBadge[1] }}</span>
                                                </td>
                                                <td>
                                                    <div class="d-flex gap-2">
                                                        <a href="{{ route('dashboard-operator.accreditations.input.index', $accreditation) }}" class="btn btn-primary btn-sm">
                                                            Input
                                                        </a>
                                                        <a href="{{ route('dashboard-operator.accreditations.indicators.index', $accreditation) }}" class="btn btn-outline-primary btn-sm">
                                                            Indikator
                                                        </a>
                                                    </div>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="5" class="text-center text-secondary py-4">
                                                    Belum ada akreditasi yang mencakup Program Studi Anda.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        @endif

    </div>

@endsection