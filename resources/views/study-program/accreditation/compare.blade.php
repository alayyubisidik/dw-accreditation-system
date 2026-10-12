@extends('layout.app')

@section('content')
    @php
        $naik = collect($rows)->where('trend', 'naik')->count();
        $turun = collect($rows)->where('trend', 'turun')->count();
        $sama = collect($rows)->where('trend', 'sama')->count();
        $belum = collect($rows)->whereNull('trend')->count();
        $pctA = $summaryA['total'] > 0 ? round($summaryA['achieved'] / $summaryA['total'] * 100) : 0;
        $pctB = $summaryB['total'] > 0 ? round($summaryB['achieved'] / $summaryB['total'] * 100) : 0;
    @endphp

    <div class="container-xl">
        <div class="card mb-3">
            <div class="card-body d-flex justify-content-between align-items-start flex-wrap gap-3">
                <div>
                    <div class="text-secondary text-uppercase fw-semibold small">Perbandingan Batch Akreditasi Program Studi</div>
                    <h2 class="mb-1">{{ $studyProgram->name }}</h2>
                    <div class="text-secondary">
                        {{ $batchA->name }} ({{ $batchA->year }}) VS {{ $batchB->name }} ({{ $batchB->year }})
                    </div>
                </div>
                <div class="d-flex gap-2 d-print-none">
                    <a href="{{ route('dashboard-admin.study-program.accreditations.compare.select') }}" class="btn btn-outline-primary btn-sm">
                        <i class="ti ti-refresh me-1"></i> Ganti Batch
                    </a>
                    <button onclick="window.print()" class="btn btn-primary btn-sm">
                        <i class="ti ti-printer me-1"></i> Cetak
                    </button>
                </div>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h4>Batch A — {{ $batchA->year }}</h4>
                        <div class="row text-center">
                            <div class="col"><div class="h2 mb-0">{{ $summaryA['total'] }}</div><div class="text-secondary">Indikator</div></div>
                            <div class="col"><div class="h2 mb-0 text-success">{{ $summaryA['achieved'] }}</div><div class="text-secondary">Tercapai</div></div>
                            <div class="col"><div class="h2 mb-0 text-danger">{{ $summaryA['filled'] - $summaryA['achieved'] }}</div><div class="text-secondary">Tdk Tercapai</div></div>
                        </div>
                        <div class="progress mt-3"><div class="progress-bar" style="width: {{ $pctA }}%"></div></div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h4>Tren Perubahan (A → B)</h4>
                        <div class="row text-center">
                            <div class="col-3"><div class="h2 mb-0 text-success">{{ $naik }}</div><div class="text-secondary">Naik</div></div>
                            <div class="col-3"><div class="h2 mb-0 text-danger">{{ $turun }}</div><div class="text-secondary">Turun</div></div>
                            <div class="col-3"><div class="h2 mb-0 text-secondary">{{ $sama }}</div><div class="text-secondary">Sama</div></div>
                            <div class="col-3"><div class="h2 mb-0 text-muted">{{ $belum }}</div><div class="text-secondary">Belum</div></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card h-100">
                    <div class="card-body">
                        <h4>Batch B — {{ $batchB->year }}</h4>
                        <div class="row text-center">
                            <div class="col"><div class="h2 mb-0">{{ $summaryB['total'] }}</div><div class="text-secondary">Indikator</div></div>
                            <div class="col"><div class="h2 mb-0 text-success">{{ $summaryB['achieved'] }}</div><div class="text-secondary">Tercapai</div></div>
                            <div class="col"><div class="h2 mb-0 text-danger">{{ $summaryB['filled'] - $summaryB['achieved'] }}</div><div class="text-secondary">Tdk Tercapai</div></div>
                        </div>
                        <div class="progress mt-3"><div class="progress-bar bg-success" style="width: {{ $pctB }}%"></div></div>
                    </div>
                </div>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Detail Perbandingan Indikator</h4>
                <span class="text-secondary">{{ count($rows) }} indikator dapat dibandingkan</span>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th width="40">No</th>
                            <th>Indikator</th>
                            <th width="110" class="text-center">Formula</th>
                            <th width="210" class="text-center">Batch A</th>
                            <th width="90" class="text-center">Tren</th>
                            <th width="210" class="text-center">Batch B</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $i => $row)
                            <tr>
                                <td class="text-center text-secondary">{{ $i + 1 }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $row['title'] ?? '-' }}</div>
                                    @if ($row['only_in_a'])
                                        <span class="badge bg-blue-lt mt-1">Hanya di Batch A</span>
                                    @elseif ($row['only_in_b'])
                                        <span class="badge bg-purple-lt mt-1">Hanya di Batch B</span>
                                    @endif
                                </td>
                                <td class="text-center">{{ $row['formula'] === 'percentage' ? 'Persentase' : 'Rasio' }}</td>
                                <td class="text-center">
                                    @if ($row['only_in_b'])
                                        <span class="text-secondary">—</span>
                                    @elseif ($row['result_a'] === null)
                                        <span class="badge bg-secondary-lt">Belum diisi</span>
                                    @else
                                        <div class="fw-bold {{ $row['achieved_a'] ? 'text-success' : 'text-danger' }}">
                                            {{ $row['formula'] === 'percentage' ? number_format($row['result_a'], 2) . '%' : number_format($row['result_a'], 2) }}
                                        </div>
                                        <small class="text-secondary">Target: {{ $row['formula'] === 'percentage' ? number_format($row['target_a'], 2) . '%' : number_format($row['target_a'], 2) }}</small>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($row['trend'] === 'naik')
                                        <span class="text-success fw-semibold">Naik</span>
                                    @elseif ($row['trend'] === 'turun')
                                        <span class="text-danger fw-semibold">Turun</span>
                                    @elseif ($row['trend'] === 'sama')
                                        <span class="text-secondary fw-semibold">Sama</span>
                                    @else
                                        <span class="text-secondary">—</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if ($row['only_in_a'])
                                        <span class="text-secondary">—</span>
                                    @elseif ($row['result_b'] === null)
                                        <span class="badge bg-secondary-lt">Belum diisi</span>
                                    @else
                                        <div class="fw-bold {{ $row['achieved_b'] ? 'text-success' : 'text-danger' }}">
                                            {{ $row['formula'] === 'percentage' ? number_format($row['result_b'], 2) . '%' : number_format($row['result_b'], 2) }}
                                        </div>
                                        <small class="text-secondary">Target: {{ $row['formula'] === 'percentage' ? number_format($row['target_b'], 2) . '%' : number_format($row['target_b'], 2) }}</small>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-secondary">Tidak ada indikator yang dapat dibandingkan.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            @if ($onlyInA->isNotEmpty() || $onlyInB->isNotEmpty())
                <div class="card-footer text-secondary">
                    {{ $onlyInA->count() + $onlyInB->count() }} indikator tidak dapat dibandingkan karena dibuat tanpa template.
                </div>
            @endif
        </div>
    </div>
@endsection
