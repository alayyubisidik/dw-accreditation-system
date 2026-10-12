@extends('layout.app')

@section('content')

    @php
        $naik   = collect($rows)->where('trend', 'naik')->count();
        $turun  = collect($rows)->where('trend', 'turun')->count();
        $sama   = collect($rows)->where('trend', 'sama')->count();
        $belum  = collect($rows)->whereNull('trend')->count();
    @endphp

    <div class="container-xl cmp-wrap">

        {{-- ── HEADER ────────────────────────────────────── --}}
        <div class="cmp-header">

            <div class="cmp-header-inner">

                <div class="cmp-eyebrow">Perbandingan Akreditasi Akreditasi Universitas</div>

                <div class="cmp-vs-row">

                    <div class="cmp-side cmp-side-a">
                        <div class="cmp-akreditasi-label">Akreditasi A</div>
                        <div class="cmp-akreditasi-name">{{ $accA->name }}</div>
                        <div class="cmp-akreditasi-meta">
                            {{ $accA->agency->name }} &middot; {{ $accA->year }}
                        </div>
                    </div>

                    <div class="cmp-vs-badge">VS</div>

                    <div class="cmp-side cmp-side-b">
                        <div class="cmp-akreditasi-label">Akreditasi B</div>
                        <div class="cmp-akreditasi-name">{{ $accB->name }}</div>
                        <div class="cmp-akreditasi-meta">
                            {{ $accB->agency->name }} &middot; {{ $accB->year }}
                        </div>
                    </div>

                </div>

            </div>

            <div class="cmp-header-actions d-print-none">
                <a href="{{ route('dashboard-admin.university.accreditations.compare.select') }}"
                    class="btn btn-outline-light btn-sm">
                    <i class="ti ti-refresh me-1"></i> Ganti Akreditasi
                </a>
                <button onclick="window.print()" class="btn btn-light btn-sm">
                    <i class="ti ti-printer me-1"></i> Cetak
                </button>
            </div>

        </div>

        {{-- ── RINGKASAN ────────────────────────────────── --}}
        <div class="row g-3 mb-4">

            {{-- Ringkasan Akreditasi A --}}
            <div class="col-md-4">
                <div class="cmp-summary-card cmp-summary-a">
                    <div class="cmp-summary-title">Akreditasi A — {{ $accA->year }}</div>
                    <div class="cmp-summary-stats">
                        <div class="cmp-sstat">
                            <span class="cmp-sstat-num">{{ $summaryA['total'] }}</span>
                            <span class="cmp-sstat-label">Indikator</span>
                        </div>
                        <div class="cmp-sstat">
                            <span class="cmp-sstat-num text-success">{{ $summaryA['achieved'] }}</span>
                            <span class="cmp-sstat-label">Tercapai</span>
                        </div>
                        <div class="cmp-sstat">
                            <span class="cmp-sstat-num text-danger">{{ $summaryA['filled'] - $summaryA['achieved'] }}</span>
                            <span class="cmp-sstat-label">Tdk Tercapai</span>
                        </div>
                    </div>
                    @php
                        $pctA = $summaryA['total'] > 0 ? round($summaryA['achieved'] / $summaryA['total'] * 100) : 0;
                    @endphp
                    <div class="cmp-summary-progress">
                        <div class="d-flex justify-content-between mb-1">
                            <small>Capaian</small><small><strong>{{ $pctA }}%</strong></small>
                        </div>
                        <div class="progress" style="height:7px">
                            <div class="progress-bar bg-primary" style="width:{{ $pctA }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Ringkasan Tren --}}
            <div class="col-md-4">
                <div class="cmp-summary-card cmp-summary-trend">
                    <div class="cmp-summary-title">Tren Perubahan (A → B)</div>
                    <div class="cmp-trend-grid">
                        <div class="cmp-trend-item cmp-trend-naik">
                            <i class="ti ti-trending-up"></i>
                            <span class="cmp-trend-num">{{ $naik }}</span>
                            <span class="cmp-trend-label">Naik</span>
                        </div>
                        <div class="cmp-trend-item cmp-trend-turun">
                            <i class="ti ti-trending-down"></i>
                            <span class="cmp-trend-num">{{ $turun }}</span>
                            <span class="cmp-trend-label">Turun</span>
                        </div>
                        <div class="cmp-trend-item cmp-trend-sama">
                            <i class="ti ti-minus"></i>
                            <span class="cmp-trend-num">{{ $sama }}</span>
                            <span class="cmp-trend-label">Sama</span>
                        </div>
                        <div class="cmp-trend-item cmp-trend-belum">
                            <i class="ti ti-circle-dashed"></i>
                            <span class="cmp-trend-num">{{ $belum }}</span>
                            <span class="cmp-trend-label">Belum diisi</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Ringkasan Akreditasi B --}}
            <div class="col-md-4">
                <div class="cmp-summary-card cmp-summary-b">
                    <div class="cmp-summary-title">Akreditasi B — {{ $accB->year }}</div>
                    <div class="cmp-summary-stats">
                        <div class="cmp-sstat">
                            <span class="cmp-sstat-num">{{ $summaryB['total'] }}</span>
                            <span class="cmp-sstat-label">Indikator</span>
                        </div>
                        <div class="cmp-sstat">
                            <span class="cmp-sstat-num text-success">{{ $summaryB['achieved'] }}</span>
                            <span class="cmp-sstat-label">Tercapai</span>
                        </div>
                        <div class="cmp-sstat">
                            <span class="cmp-sstat-num text-danger">{{ $summaryB['filled'] - $summaryB['achieved'] }}</span>
                            <span class="cmp-sstat-label">Tdk Tercapai</span>
                        </div>
                    </div>
                    @php
                        $pctB = $summaryB['total'] > 0 ? round($summaryB['achieved'] / $summaryB['total'] * 100) : 0;
                    @endphp
                    <div class="cmp-summary-progress">
                        <div class="d-flex justify-content-between mb-1">
                            <small>Capaian</small><small><strong>{{ $pctB }}%</strong></small>
                        </div>
                        <div class="progress" style="height:7px">
                            <div class="progress-bar bg-success" style="width:{{ $pctB }}%"></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        {{-- ── TABEL PERBANDINGAN ──────────────────────── --}}
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0">Detail Perbandingan Indikator</h4>
                <span class="text-secondary">{{ count($rows) }} indikator dapat dibandingkan</span>
            </div>
            <div class="table-responsive">
                <table class="table table-bordered cmp-table mb-0">
                    <thead>
                        <tr>
                            <th width="40">No</th>
                            <th>Indikator</th>
                            <th width="100">Formula</th>
                            <th width="200" class="text-center cmp-col-a">
                                Akreditasi A<br>
                                <small class="text-secondary fw-normal">{{ $accA->year }}</small>
                            </th>
                            <th width="80" class="text-center">Tren</th>
                            <th width="200" class="text-center cmp-col-b">
                                Akreditasi B<br>
                                <small class="text-secondary fw-normal">{{ $accB->year }}</small>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($rows as $i => $row)
                            @php
                                $rowClass = match(true) {
                                    $row['only_in_a'] => 'cmp-row-only-a',
                                    $row['only_in_b'] => 'cmp-row-only-b',
                                    default           => '',
                                };
                            @endphp
                            <tr class="{{ $rowClass }}">

                                {{-- No --}}
                                <td class="text-center text-secondary">{{ $i + 1 }}</td>

                                {{-- Judul + keterangan --}}
                                <td>
                                    <div class="fw-semibold">{{ $row['title'] ?? '-' }}</div>
                                    @if ($row['only_in_a'])
                                        <span class="badge bg-blue-lt mt-1">Hanya di Akreditasi A</span>
                                    @elseif ($row['only_in_b'])
                                        <span class="badge bg-purple-lt mt-1">Hanya di Akreditasi B</span>
                                    @endif
                                </td>

                                {{-- Formula --}}
                                <td class="text-center">
                                    <span class="badge bg-secondary-lt">
                                        {{ $row['formula'] === 'percentage' ? 'Persentase' : 'Rasio' }}
                                    </span>
                                </td>

                                {{-- Hasil Akreditasi A --}}
                                <td class="cmp-col-a">
                                    @if ($row['only_in_b'])
                                        <span class="text-secondary">—</span>
                                    @elseif ($row['result_a'] === null)
                                        <span class="cmp-badge cmp-badge-gray">Belum diisi</span>
                                    @else
                                        @php
                                            $cls = $row['achieved_a'] ? 'cmp-badge-green' : 'cmp-badge-red';
                                        @endphp
                                        <div class="cmp-result-main {{ $row['achieved_a'] ? 'text-success' : 'text-danger' }}">
                                            {{ $row['formula'] === 'percentage' ? number_format($row['result_a'], 2) . '%' : number_format($row['result_a'], 2) }}
                                        </div>
                                        <div class="cmp-result-sub">
                                            Target: {{ $row['formula'] === 'percentage' ? number_format($row['target_a'], 2) . '%' : number_format($row['target_a'], 2) }}
                                        </div>
                                        @if ($row['gap_a'] !== null)
                                            <div class="cmp-result-sub">
                                                Gap: {{ number_format($row['gap_a'], 2) }} {{ $row['gap_unit'] }}
                                            </div>
                                        @endif
                                        <span class="cmp-badge {{ $cls }}">
                                            {{ $row['achieved_a'] ? 'Tercapai' : 'Belum Tercapai' }}
                                        </span>
                                    @endif
                                </td>

                                {{-- Tren --}}
                                <td class="text-center cmp-trend-cell">
                                    @if ($row['trend'] === 'naik')
                                        <div class="cmp-trend-icon cmp-trend-icon-naik">
                                            <i class="ti ti-trending-up"></i>
                                        </div>
                                        <div class="cmp-trend-text cmp-trend-text-naik">Naik</div>
                                    @elseif ($row['trend'] === 'turun')
                                        <div class="cmp-trend-icon cmp-trend-icon-turun">
                                            <i class="ti ti-trending-down"></i>
                                        </div>
                                        <div class="cmp-trend-text cmp-trend-text-turun">Turun</div>
                                    @elseif ($row['trend'] === 'sama')
                                        <div class="cmp-trend-icon cmp-trend-icon-sama">
                                            <i class="ti ti-minus"></i>
                                        </div>
                                        <div class="cmp-trend-text cmp-trend-text-sama">Sama</div>
                                    @else
                                        <span class="text-secondary">—</span>
                                    @endif
                                </td>

                                {{-- Hasil Akreditasi B --}}
                                <td class="cmp-col-b">
                                    @if ($row['only_in_a'])
                                        <span class="text-secondary">—</span>
                                    @elseif ($row['result_b'] === null)
                                        <span class="cmp-badge cmp-badge-gray">Belum diisi</span>
                                    @else
                                        @php
                                            $cls = $row['achieved_b'] ? 'cmp-badge-green' : 'cmp-badge-red';
                                        @endphp
                                        <div class="cmp-result-main {{ $row['achieved_b'] ? 'text-success' : 'text-danger' }}">
                                            {{ $row['formula'] === 'percentage' ? number_format($row['result_b'], 2) . '%' : number_format($row['result_b'], 2) }}
                                        </div>
                                        <div class="cmp-result-sub">
                                            Target: {{ $row['formula'] === 'percentage' ? number_format($row['target_b'], 2) . '%' : number_format($row['target_b'], 2) }}
                                        </div>
                                        @if ($row['gap_b'] !== null)
                                            <div class="cmp-result-sub">
                                                Gap: {{ number_format($row['gap_b'], 2) }} {{ $row['gap_unit'] }}
                                            </div>
                                        @endif
                                        <span class="cmp-badge {{ $cls }}">
                                            {{ $row['achieved_b'] ? 'Tercapai' : 'Belum Tercapai' }}
                                        </span>
                                    @endif
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center py-4 text-secondary">
                                    Tidak ada indikator yang dapat dibandingkan.<br>
                                    <small>Pastikan kedua akreditasi menggunakan template indikator yang sama.</small>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Indikator tanpa template (tidak bisa dibandingkan) --}}
            @if ($onlyInA->isNotEmpty() || $onlyInB->isNotEmpty())
                <div class="card-footer">
                    <details>
                        <summary class="text-secondary" style="cursor:pointer">
                            <i class="ti ti-info-circle me-1"></i>
                            {{ $onlyInA->count() + $onlyInB->count() }} indikator tidak dapat dibandingkan
                            (dibuat tanpa template)
                        </summary>
                        <div class="mt-3">
                            @if ($onlyInA->isNotEmpty())
                                <div class="mb-2">
                                    <strong>Hanya di Akreditasi A ({{ $accA->year }}):</strong>
                                    <ul class="mb-0 mt-1">
                                        @foreach ($onlyInA as $ind)
                                            <li class="text-secondary">{{ $ind->title }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            @if ($onlyInB->isNotEmpty())
                                <div>
                                    <strong>Hanya di Akreditasi B ({{ $accB->year }}):</strong>
                                    <ul class="mb-0 mt-1">
                                        @foreach ($onlyInB as $ind)
                                            <li class="text-secondary">{{ $ind->title }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                        </div>
                    </details>
                </div>
            @endif

        </div>

    </div>

@endsection

@push('css')
<style>
/* ─── Wrapper ─────────────────────────────────────── */
.cmp-wrap {
    --cmp-a: #2D5FA1;
    --cmp-a-soft: #EBF1FB;
    --cmp-b: #2F7D5C;
    --cmp-b-soft: #E4F1EB;
    --cmp-naik:  #2F7D5C;
    --cmp-turun: #B8433D;
    --cmp-sama:  #7C879B;
    --cmp-gray-soft: #EDEFF3;
}

/* ─── Header ──────────────────────────────────────── */
.cmp-header {
    background: #1B2A4A;
    border-radius: 10px;
    padding: 24px 28px;
    margin-bottom: 24px;
    display: flex;
    justify-content: space-between;
    align-items: center;
    gap: 16px;
    flex-wrap: wrap;
}

.cmp-eyebrow {
    text-transform: uppercase;
    letter-spacing: .1em;
    font-size: 11.5px;
    font-weight: 600;
    color: #B8873D;
    margin-bottom: 14px;
}

.cmp-vs-row {
    display: flex;
    align-items: center;
    gap: 20px;
}

.cmp-side {
    flex: 1;
}

.cmp-akreditasi-label {
    font-size: 11px;
    text-transform: uppercase;
    letter-spacing: .08em;
    font-weight: 700;
    margin-bottom: 4px;
}

.cmp-side-a .cmp-akreditasi-label { color: #7EB0F8; }
.cmp-side-b .cmp-akreditasi-label { color: #72CFA8; }

.cmp-akreditasi-name {
    font-size: 18px;
    font-weight: 700;
    color: #fff;
    line-height: 1.2;
}

.cmp-akreditasi-meta {
    font-size: 13px;
    color: rgba(255,255,255,.55);
    margin-top: 3px;
}

.cmp-vs-badge {
    font-size: 20px;
    font-weight: 900;
    color: #B8873D;
    background: rgba(184,135,61,.18);
    border-radius: 50%;
    width: 48px;
    height: 48px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

/* ─── Summary Cards ───────────────────────────────── */
.cmp-summary-card {
    background: #fff;
    border: 1px solid #E4DFD2;
    border-radius: 10px;
    padding: 18px 20px;
    height: 100%;
}

.cmp-summary-a { border-top: 3px solid var(--cmp-a); }
.cmp-summary-b { border-top: 3px solid var(--cmp-b); }
.cmp-summary-trend { border-top: 3px solid #B8873D; }

.cmp-summary-title {
    font-size: 13px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .06em;
    color: #5B667A;
    margin-bottom: 14px;
}

.cmp-summary-stats {
    display: flex;
    gap: 18px;
    margin-bottom: 16px;
}

.cmp-sstat {
    display: flex;
    flex-direction: column;
}

.cmp-sstat-num {
    font-size: 26px;
    font-weight: 700;
    line-height: 1;
}

.cmp-sstat-label {
    font-size: 12px;
    color: #7C879B;
    margin-top: 2px;
}

/* ─── Trend Grid ──────────────────────────────────── */
.cmp-trend-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 10px;
}

.cmp-trend-item {
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 10px 6px;
    border-radius: 8px;
    gap: 2px;
}

.cmp-trend-item i { font-size: 20px; }

.cmp-trend-num {
    font-size: 22px;
    font-weight: 700;
    line-height: 1;
}

.cmp-trend-label {
    font-size: 11px;
    color: #5B667A;
}

.cmp-trend-naik  { background: #E4F1EB; color: var(--cmp-naik); }
.cmp-trend-turun { background: #F6E6E4; color: var(--cmp-turun); }
.cmp-trend-sama  { background: #EDEFF3; color: var(--cmp-sama); }
.cmp-trend-belum { background: #F5F5F5; color: #aaa; }

/* ─── Table ───────────────────────────────────────── */
.cmp-table { font-size: 14px; }

.cmp-table thead th {
    background: #F8F9FB;
    font-weight: 600;
    vertical-align: middle;
}

.cmp-table td, .cmp-table th {
    vertical-align: middle;
    padding: 12px 14px;
}

.cmp-col-a { background: #F5F8FD; }
.cmp-col-b { background: #F4FBF8; }

.cmp-row-only-a td { opacity: .75; }
.cmp-row-only-b td { opacity: .75; }

/* ─── Result cells ────────────────────────────────── */
.cmp-result-main {
    font-size: 18px;
    font-weight: 700;
    line-height: 1.1;
    margin-bottom: 3px;
}

.cmp-result-sub {
    font-size: 12px;
    color: #7C879B;
    line-height: 1.4;
}

/* ─── Badges ──────────────────────────────────────── */
.cmp-badge {
    display: inline-block;
    font-size: 11.5px;
    font-weight: 600;
    padding: 3px 10px;
    border-radius: 999px;
    margin-top: 5px;
    white-space: nowrap;
}

.cmp-badge-green { background: #E4F1EB; color: #2F7D5C; }
.cmp-badge-red   { background: #F6E6E4; color: #B8433D; }
.cmp-badge-gray  { background: #EDEFF3; color: #7C879B; }

/* ─── Trend cell ──────────────────────────────────── */
.cmp-trend-cell { white-space: nowrap; }

.cmp-trend-icon {
    font-size: 22px;
    line-height: 1;
}

.cmp-trend-text {
    font-size: 11px;
    font-weight: 600;
    margin-top: 2px;
}

.cmp-trend-icon-naik,  .cmp-trend-text-naik  { color: var(--cmp-naik); }
.cmp-trend-icon-turun, .cmp-trend-text-turun { color: var(--cmp-turun); }
.cmp-trend-icon-sama,  .cmp-trend-text-sama  { color: var(--cmp-sama); }

/* ─── Print ───────────────────────────────────────── */
@media print {
    .navbar, .footer, .d-print-none { display: none !important; }
    .cmp-header {
        background: #1B2A4A !important;
        -webkit-print-color-adjust: exact;
        print-color-adjust: exact;
    }
    .cmp-col-a { background: #F5F8FD !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .cmp-col-b { background: #F4FBF8 !important; -webkit-print-color-adjust: exact; print-color-adjust: exact; }
    .cmp-table tr { break-inside: avoid; }
}
</style>
@endpush
