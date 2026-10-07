@extends('layout.app')

@section('content')
    <div class="container-xl">

        {{-- HEADER --}}
        <div class="card mb-3">

            <div class="card-body">

                <div class="d-flex justify-content-between align-items-center">

                    <div>
                        <h2 class="mb-1">
                            Isi Data Indikator
                        </h2>

                        <div class="text-secondary">
                            {{ $accreditation->batch->name ?? '-' }}
                            &middot; {{ $accreditation->batch->agency->name ?? '-' }}
                        </div>
                    </div>

                    <a href="{{ route('dashboard-admin.university.accreditations.input.index', $accreditation) }}" class="btn btn-secondary">
                        Kembali
                    </a>

                </div>

            </div>

        </div>

        {{-- INFO INDIKATOR --}}
        <div class="card mb-3">

            <div class="card-header">
                <h3 class="card-title">
                    {{ $indicator->title }}
                </h3>
            </div>

            <div class="card-body">

                @if ($indicator->description)
                    <p class="text-secondary">
                        {{ $indicator->description }}
                    </p>
                @endif

                <div class="row">

                    <div class="col-md-4">
                        <div class="text-secondary small">Jenis Formula</div>
                        <div class="fw-bold">
                            {{ $indicator->formula_type === 'percentage' ? 'Persentase' : 'Rasio' }}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="text-secondary small">Target</div>
                        <div class="fw-bold">
                            {{ number_format($indicator->target, 2) }}{{ $indicator->formula_type === 'percentage' ? '%' : '' }}
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="text-secondary small">Rumus</div>
                        <div class="fw-bold">
                            @if ($indicator->formula_type === 'percentage')
                                ({{ $indicator->label_1 }} / {{ $indicator->label_2 }}) &times; 100
                            @else
                                {{ $indicator->label_1 }} / {{ $indicator->label_2 }}
                            @endif
                        </div>
                    </div>

                </div>

            
            </div>

        </div>

        {{-- FORM --}}
        <div class="card">

            <div class="card-header">
                <h3 class="card-title">
                    Input Nilai
                </h3>
            </div>

            <form action="{{ route('dashboard-admin.university.accreditations.input.update', [$accreditation, $indicator]) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="card-body">

                    <div class="row">

                        {{-- VALUE 1 --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label required">
                                {{ $indicator->label_1 }}
                            </label>

                            <input type="number" step="0.01" min="0" name="value_1" id="value_1"
                                class="form-control" value="{{ old('value_1', $indicator->value_1) }}"
                                placeholder="Masukkan angka {{ $indicator->label_1 }}">

                            <div class="form-hint">
                                Ini adalah pembilang (bagian atas) dari rumus.
                            </div>

                            <x-input-error :messages="$errors->get('value_1')" />

                        </div>

                        {{-- VALUE 2 --}}
                        <div class="col-md-6 mb-3">

                            <label class="form-label required">
                                {{ $indicator->label_2 ?? 'Penyebut' }}
                            </label>

                            <input type="number" step="0.01" min="0.01" name="value_2" id="value_2"
                                class="form-control" value="{{ old('value_2', $indicator->value_2) }}"
                                placeholder="Masukkan angka {{ $indicator->label_2 ?? 'penyebut' }}">

                            <div class="form-hint">
                                Ini adalah penyebut (bagian bawah) dari rumus. Tidak boleh 0.
                            </div>

                            <x-input-error :messages="$errors->get('value_2')" />

                        </div>

                    </div>

                    {{-- PREVIEW HASIL --}}
                    <div class="card bg-light">

                        <div class="card-body">

                            <h4 class="mb-3">
                                Pratinjau Hasil Perhitungan
                            </h4>

                            <div class="row text-center">

                                <div class="col-md-4">
                                    <div class="text-secondary small">Hasil Capaian</div>
                                    <div class="h2 mb-0" id="preview-result">-</div>
                                </div>

                                <div class="col-md-4">
                                    <div class="text-secondary small">Target (dalam angka)</div>
                                    <div class="h2 mb-0" id="preview-target-absolute">-</div>
                                </div>

                                <div class="col-md-4">
                                    <div class="text-secondary small">Status</div>
                                    <div class="h2 mb-0" id="preview-status">-</div>
                                </div>

                            </div>

                            <div class="text-center mt-2">
                                <span id="preview-gap-text" class="fw-bold"></span>
                            </div>

                            <div class="form-hint text-center mt-2">
                                Perhitungan ini otomatis berdasarkan angka yang kamu masukkan di atas. Simpan untuk
                                menyimpan hasil final.
                            </div>

                        </div>

                    </div>

                </div>

                <div class="card-footer text-end">

                    <a href="{{ route('dashboard-admin.university.accreditations.input.index', $accreditation) }}" class="btn btn-link">
                        Batal
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Simpan Data
                    </button>

                </div>

            </form>

        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const formulaType = "{{ $indicator->formula_type }}";
            const target = {{ (float) $indicator->target }};
            const gapUnit = @json($indicator->gap_unit);

            const value1Input = document.getElementById('value_1');
            const value2Input = document.getElementById('value_2');

            const previewResult = document.getElementById('preview-result');
            const previewTargetAbsolute = document.getElementById('preview-target-absolute');
            const previewStatus = document.getElementById('preview-status');
            const previewGapText = document.getElementById('preview-gap-text');

            function formatNumber(num) {
                return new Intl.NumberFormat('id-ID', {
                    maximumFractionDigits: 2
                }).format(num);
            }

            function calculate() {

                const value1 = parseFloat(value1Input.value);
                const value2 = parseFloat(value2Input.value);

                if (isNaN(value1) || isNaN(value2) || value2 <= 0) {
                    previewResult.textContent = '-';
                    previewTargetAbsolute.textContent = '-';
                    previewStatus.textContent = '-';
                    previewGapText.textContent = '';
                    return;
                }

                let result, targetAbsolute;

                if (formulaType === 'percentage') {
                    result = (value1 / value2) * 100;
                    targetAbsolute = (target / 100) * value2;
                } else {
                    result = value1 / value2;
                    targetAbsolute = target * value2;
                }

                const gap = Math.max(targetAbsolute - value1, 0);
                const achieved = result >= target;

                previewResult.textContent = formatNumber(result) + (formulaType === 'percentage' ? '%' : '');
                previewResult.className = 'h2 mb-0 ' + (achieved ? 'text-success' : 'text-danger');

                previewTargetAbsolute.textContent = formatNumber(targetAbsolute) + (gapUnit ? ' ' + gapUnit : '');

                if (achieved) {
                    previewStatus.innerHTML = '<span class="badge bg-success text-white">Tercapai ✓</span>';
                    previewGapText.className = 'fw-bold text-success';
                    previewGapText.textContent = 'Target sudah tercapai, kerja bagus!';
                } else {
                    previewStatus.innerHTML = '<span class="badge bg-danger text-white">Belum Tercapai ✗</span>';
                    previewGapText.className = 'fw-bold text-danger';
                    previewGapText.textContent = 'Masih kurang ' + formatNumber(gap) + (gapUnit ? ' ' + gapUnit :
                        '') + ' lagi untuk mencapai target.';
                }
            }

            value1Input.addEventListener('input', calculate);
            value2Input.addEventListener('input', calculate);

            calculate();

        });
    </script>
@endsection

