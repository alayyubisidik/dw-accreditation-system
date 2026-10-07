@extends('layout.app')

@section('content')

    @php
        $indicators = $accreditation->indicators;
        $total = $indicators->count();

        $tercapai = $indicators->filter(fn ($i) => $i->result !== null && $i->result >= $i->target)->count();
        $belumTercapai = $indicators->filter(fn ($i) => $i->result !== null && $i->result < $i->target)->count();
        $belumDiisi = $indicators->filter(fn ($i) => $i->result === null)->count();

        $persenCapaian = $total > 0 ? round(($tercapai / $total) * 100) : 0;
    @endphp

    <div class="container-xl accred-preview">

        <div class="card">

            <div class="card-header ap-header">

                {{-- LEFT SIDE --}}
                <div class="ap-header-info">

                    <div class="ap-eyebrow">
                        {{ $accreditation->batch->agency->name }}
                    </div>

                    <h2 class="ap-title">
                        Preview Dokumen Akreditasi
                    </h2>

                    <div class="ap-meta-grid">
                        <div class="ap-meta-item">
                            <span class="ap-meta-label">Batch</span>
                            <span class="ap-meta-value">{{ $accreditation->batch->name }}</span>
                        </div>
                        <div class="ap-meta-item">
                            <span class="ap-meta-label">Tahun</span>
                            <span class="ap-meta-value">{{ $accreditation->batch->year }}</span>
                        </div>
                        <div class="ap-meta-item">
                            <span class="ap-meta-label">Status</span>
                            <span class="ap-meta-value">
                                @switch($accreditation->status)
                                    @case('not_imported')
                                        <span class="ap-badge ap-badge-gray">Belum Import</span>
                                    @break

                                    @case('in_progress')
                                        <span class="ap-badge ap-badge-gold">Sedang Dikerjakan</span>
                                    @break

                                    @case('completed')
                                        <span class="ap-badge ap-badge-green">Selesai</span>
                                    @break
                                @endswitch
                            </span>
                        </div>
                    </div>
                </div>

                {{-- RIGHT SIDE --}}
                <div class="ap-header-actions d-print-none">
                    <div class="ap-mini-seal">
                        <span class="ap-mini-seal-number">{{ $persenCapaian }}%</span>
                        <span class="ap-mini-seal-label">Tercapai</span>
                    </div>
                    <button onclick="window.print()" class="btn btn-primary">
                        Cetak
                    </button>
                </div>

            </div>

            <div class="card-body">

                @if ($accreditation->batch->description)
                    <div class="ap-note">
                        {{ $accreditation->batch->description }}
                    </div>
                @endif

                {{-- RINGKASAN ANGKA --}}
                <div class="ap-summary">
                    <div class="ap-stat">
                        <span class="ap-stat-number">{{ $total }}</span>
                        <span class="ap-stat-label">Total Indikator</span>
                    </div>
                    <div class="ap-stat ap-stat-green">
                        <span class="ap-stat-number">{{ $tercapai }}</span>
                        <span class="ap-stat-label">Tercapai</span>
                    </div>
                    <div class="ap-stat ap-stat-red">
                        <span class="ap-stat-number">{{ $belumTercapai }}</span>
                        <span class="ap-stat-label">Belum Tercapai</span>
                    </div>
                    <div class="ap-stat ap-stat-gray">
                        <span class="ap-stat-number">{{ $belumDiisi }}</span>
                        <span class="ap-stat-label">Belum Diisi</span>
                    </div>
                </div>

                <div class="table-responsive">

                    <table class="table table-bordered ap-table">

                        <thead>

                            <tr>

                                <th width="50">No</th>

                                <th>Indikator</th>

                                <th width="110">Formula</th>

                                <th width="200">Capaian</th>

                                <th width="150">Status</th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($indicators as $indicator)
                                @php
                                    $result = $indicator->result;
                                    $target = $indicator->target;

                                    $isAchieved = $result !== null && $result >= $target;

                                    $barMax    = max((float) $target, (float) ($result ?? 0), 1);
                                    $targetPct = min(100, ($target / $barMax) * 100);
                                    $resultPct = $result !== null ? min(100, ($result / $barMax) * 100) : 0;
                                @endphp

                                <tr>

                                    <td class="ap-td-index">
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>

                                        <strong class="ap-indicator-title">
                                            {{ $indicator->title }}
                                        </strong>

                                        @if ($indicator->description)
                                            <div class="ap-indicator-desc">
                                                {{ $indicator->description }}
                                            </div>
                                        @endif

                                        @if ($indicator->value_1 !== null)
                                            <div class="ap-indicator-values">

                                                {{ $indicator->label_1 }} :
                                                <strong>{{ number_format($indicator->value_1, 2) }}</strong>

                                                @if ($indicator->label_2)
                                                    &nbsp;&middot;&nbsp;

                                                    {{ $indicator->label_2 }} :
                                                    <strong>{{ number_format($indicator->value_2, 2) }}</strong>
                                                @endif

                                            </div>
                                        @endif

                                    </td>

                                    <td class="ap-td-formula">

                                        @if ($indicator->formula_type == 'percentage')
                                            Persentase
                                        @else
                                            Rasio
                                        @endif

                                    </td>

                                    <td>
                                        <div class="ap-progress-track">
                                            <div class="ap-progress-fill {{ $isAchieved ? 'ap-progress-fill-green' : 'ap-progress-fill-red' }}"
                                                 style="width: {{ $resultPct }}%"></div>
                                            <div class="ap-progress-target" style="left: {{ $targetPct }}%"></div>
                                        </div>
                                        <div class="ap-progress-legend">
                                            <span>Hasil: <strong>{{ $result !== null ? number_format($result, 2) : '-' }}</strong></span>
                                            <span>Target: <strong>{{ number_format($target, 2) }}</strong></span>
                                        </div>

                                        @if ($indicator->gap !== null)
                                            <div class="ap-gap {{ $isAchieved ? 'ap-gap-green' : 'ap-gap-red' }}">
                                                Kesenjangan: {{ number_format($indicator->gap, 2) }} {{ $indicator->gap_unit }}
                                            </div>
                                        @endif
                                    </td>

                                    <td class="ap-td-status">

                                        @if ($result === null)
                                            <span class="ap-badge ap-badge-gray">
                                                Belum Diisi
                                            </span>
                                        @elseif ($isAchieved)
                                            <span class="ap-badge ap-badge-green">
                                                Tercapai
                                            </span>
                                        @else
                                            <span class="ap-badge ap-badge-red">
                                                Belum Tercapai
                                            </span>
                                        @endif

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="5" class="text-center">
                                        Belum ada indikator
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

@push('css')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Lora:wght@500;600;700&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">

    <style>
        .accred-preview {
            --ap-ink: #1B2A4A;
            --ap-ink-soft: #5B667A;
            --ap-gold: #B8873D;
            --ap-gold-soft: #F3E7D2;
            --ap-cream: #FBF9F5;
            --ap-paper: #FFFFFF;
            --ap-green: #2F7D5C;
            --ap-green-soft: #E4F1EB;
            --ap-red: #B8433D;
            --ap-red-soft: #F6E6E4;
            --ap-gray: #7C879B;
            --ap-gray-soft: #EDEFF3;
            --ap-border: #E4DFD2;

            font-family: 'Inter', sans-serif;
            color: var(--ap-ink);
        }

        /* ---------- Header ---------- */
        .accred-preview .ap-header {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 20px;
            flex-wrap: wrap;
            background: var(--ap-ink);
            color: var(--ap-cream);
            border-radius: 8px 8px 0 0;
        }

        .accred-preview .ap-eyebrow {
            text-transform: uppercase;
            letter-spacing: 0.1em;
            font-size: 12.5px;
            font-weight: 600;
            color: var(--ap-gold);
            margin-bottom: 6px;
        }

        .accred-preview .ap-title {
            font-family: 'Lora', serif;
            font-weight: 700;
            font-size: 24px;
            color: #FFFFFF;
            margin-bottom: 16px;
        }

        .accred-preview .ap-meta-grid {
            display: flex;
            flex-wrap: wrap;
            gap: 22px;
        }

        .accred-preview .ap-meta-item {
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .accred-preview .ap-meta-label {
            font-size: 12px;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            color: rgba(251, 249, 245, 0.6);
        }

        .accred-preview .ap-meta-value {
            font-size: 15.5px;
            font-weight: 600;
            color: #FFFFFF;
        }

        .accred-preview .ap-header-actions {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .accred-preview .ap-mini-seal {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            width: 64px;
            height: 64px;
            border-radius: 50%;
            border: 3px solid var(--ap-gold);
            line-height: 1.1;
        }

        .accred-preview .ap-mini-seal-number {
            font-family: 'Lora', serif;
            font-weight: 700;
            font-size: 15px;
            color: #FFFFFF;
        }

        .accred-preview .ap-mini-seal-label {
            font-size: 9.5px;
            color: rgba(251, 249, 245, 0.65);
        }

        /* ---------- Note ---------- */
        .accred-preview .ap-note {
            background: var(--ap-gold-soft);
            border: 1px solid var(--ap-border);
            border-radius: 10px;
            padding: 14px 18px;
            margin-bottom: 20px;
        }

        /* ---------- Summary stats ---------- */
        .accred-preview .ap-summary {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 12px;
            margin-bottom: 22px;
        }

        .accred-preview .ap-stat {
            background: var(--ap-cream);
            border: 1px solid var(--ap-border);
            border-radius: 10px;
            padding: 14px 16px;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }

        .accred-preview .ap-stat-number {
            font-family: 'Lora', serif;
            font-weight: 700;
            font-size: 24px;
        }

        .accred-preview .ap-stat-label {
            font-size: 12.5px;
            color: var(--ap-ink-soft);
        }

        .accred-preview .ap-stat-green .ap-stat-number { color: var(--ap-green); }
        .accred-preview .ap-stat-red .ap-stat-number { color: var(--ap-red); }
        .accred-preview .ap-stat-gray .ap-stat-number { color: var(--ap-gray); }

        /* ---------- Badges ---------- */
        .accred-preview .ap-badge {
            display: inline-block;
            font-size: 12.5px;
            font-weight: 600;
            padding: 4px 12px;
            border-radius: 999px;
            white-space: nowrap;
        }

        .accred-preview .ap-badge-green { background: var(--ap-green-soft); color: var(--ap-green); }
        .accred-preview .ap-badge-red { background: var(--ap-red-soft); color: var(--ap-red); }
        .accred-preview .ap-badge-gray { background: var(--ap-gray-soft); color: var(--ap-gray); }
        .accred-preview .ap-badge-gold { background: rgba(184, 135, 61, 0.18); color: var(--ap-gold); }

        /* ---------- Table ---------- */
        .accred-preview .ap-table {
            font-size: 15px;
        }

        .accred-preview .ap-table thead th {
            background: var(--ap-cream);
            color: var(--ap-ink);
            font-weight: 600;
            border-color: var(--ap-border);
        }

        .accred-preview .ap-table tbody tr:nth-child(even) {
            background: #FCFBF8;
        }

        .accred-preview .ap-table td,
        .accred-preview .ap-table th {
            vertical-align: middle;
            border-color: var(--ap-border);
            padding: 14px 12px;
        }

        .accred-preview .ap-td-index {
            text-align: center;
            font-weight: 600;
            color: var(--ap-gold);
        }

        .accred-preview .ap-td-formula,
        .accred-preview .ap-td-status {
            text-align: center;
        }

        .accred-preview .ap-indicator-title {
            font-size: 15.5px;
        }

        .accred-preview .ap-indicator-desc {
            color: var(--ap-ink-soft);
            margin-top: 4px;
            font-size: 14px;
        }

        .accred-preview .ap-indicator-values {
            font-size: 13.5px;
            color: var(--ap-ink-soft);
            margin-top: 8px;
        }

        /* ---------- Progress bar ---------- */
        .accred-preview .ap-progress-track {
            position: relative;
            height: 9px;
            background: var(--ap-gray-soft);
            border-radius: 999px;
        }

        .accred-preview .ap-progress-fill {
            height: 100%;
            border-radius: 999px;
        }

        .accred-preview .ap-progress-fill-green { background: var(--ap-green); }
        .accred-preview .ap-progress-fill-red { background: var(--ap-red); }

        .accred-preview .ap-progress-target {
            position: absolute;
            top: -3px;
            width: 3px;
            height: 15px;
            background: var(--ap-ink);
            border-radius: 2px;
        }

        .accred-preview .ap-progress-legend {
            display: flex;
            justify-content: space-between;
            font-size: 12px;
            color: var(--ap-ink-soft);
            margin-top: 6px;
        }

        .accred-preview .ap-gap {
            margin-top: 8px;
            font-size: 12px;
            padding: 3px 9px;
            border-radius: 6px;
            display: inline-block;
        }

        .accred-preview .ap-gap-green { background: var(--ap-green-soft); color: var(--ap-green); }
        .accred-preview .ap-gap-red { background: var(--ap-red-soft); color: var(--ap-red); }

        /* ---------- Responsive ---------- */
        @media (max-width: 768px) {
            .accred-preview .ap-summary { grid-template-columns: repeat(2, 1fr); }
        }

        /* ---------- Print ---------- */
        @media print {

            body {
                background: white !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .navbar,
            .btn,
            .page-header {
                display: none !important;
            }

            .container-xl,
            .card,
            .card-body,
            table {
                display: block !important;
                visibility: visible !important;
            }

            table {
                width: 100% !important;
            }

            .card {
                box-shadow: none !important;
                border: none !important;
            }

            .accred-preview .ap-header {
                background: var(--ap-ink) !important;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }

            .accred-preview tr {
                break-inside: avoid;
            }
        }
    </style>
@endpush
