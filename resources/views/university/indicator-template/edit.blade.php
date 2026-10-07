@extends('layout.app')

@section('content')
    <div class="container-xl">

        <div class="card">

            {{-- HEADER --}}
            <div class="card-header">

                <div>
                    <h3 class="card-title">
                        Edit Indikator
                    </h3>

                    <div class="text-secondary">
                        {{ $accreditationAgency->name }}
                    </div>
                </div>

                <div class="card-actions">
                    <a href="{{ route('dashboard-admin.university.accreditation-agencies.indicator-templates.index', $accreditationAgency) }}"
                        class="btn btn-secondary">
                        Kembali
                    </a>
                </div>
            </div>

            {{-- FORM --}}
            <form
                action="{{ route('dashboard-admin.university.accreditation-agencies.indicator-templates.update', [$accreditationAgency, $indicatorTemplate]) }}"
                method="POST">

                @csrf
                @method('PUT')

                <div class="card-body">

                    {{-- TITLE --}}
                    <div class="mb-3">

                        <label class="form-label required">
                            Judul Indikator
                        </label>

                        <input type="text" name="title" class="form-control"
                            value="{{ old('title', $indicatorTemplate->title) }}">

                        <x-input-error :messages="$errors->get('title')" />

                    </div>

                    {{-- DESCRIPTION --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Deskripsi
                        </label>

                        <textarea name="description" rows="3" class="form-control">{{ old('description', $indicatorTemplate->description) }}</textarea>

                        <x-input-error :messages="$errors->get('description')" />

                    </div>

                    {{-- FORMULA TYPE --}}
                    <div class="mb-4">

                        <label class="form-label required">
                            Jenis Indikator
                        </label>

                        <select name="formula_type" id="formula_type" class="form-select">

                            <option value="percentage"
                                {{ old('formula_type', $indicatorTemplate->formula_type) == 'percentage' ? 'selected' : '' }}>
                                Persentase
                            </option>

                            <option value="ratio"
                                {{ old('formula_type', $indicatorTemplate->formula_type) == 'ratio' ? 'selected' : '' }}>
                                Rasio
                            </option>

                        </select>

                        <x-input-error :messages="$errors->get('formula_type')" />

                    </div>

                    {{-- ========================= --}}
                    {{-- PERCENTAGE SECTION --}}
                    {{-- ========================= --}}
                    <div id="percentage-section">

                        <div class="card bg-light mb-4">

                            <div class="card-body">

                                <h4 class="mb-3">
                                    Formula Persentase
                                </h4>

                                <div class="alert alert-info">
                                    Formula:
                                    <strong>(Pembilang / Penyebut) × 100</strong>
                                </div>

                                <div class="row">

                                    <div class="col-md-6 mb-3">

                                        <label class="form-label">
                                            Label Pembilang
                                        </label>

                                        <input type="text" name="label_1" class="form-control"
                                            value="{{ old('label_1', $indicatorTemplate->label_1) }}"
                                            placeholder="Contoh: Jumlah Lulusan Tepat Waktu">

                                        <x-input-error :messages="$errors->get('label_1')" />

                                    </div>

                                    <div class="col-md-6 mb-3">

                                        <label class="form-label">
                                            Label Penyebut
                                        </label>

                                        <input type="text" name="label_2" class="form-control"
                                            value="{{ old('label_2', $indicatorTemplate->label_2) }}"
                                            placeholder="Contoh: Total Lulusan">

                                        <x-input-error :messages="$errors->get('label_2')" />

                                    </div>

                                </div>

                                <div>

                                    <label class="form-label">
                                        Target (%)
                                    </label>

                                    <input type="number" name="target" step="0.01" min="0" max="100"
                                        class="form-control" value="{{ old('target', $indicatorTemplate->target) }}">

                                    <x-input-error :messages="$errors->get('target')" />

                                </div>

                            </div>

                        </div>

                    </div>

                    {{-- ========================= --}}
                    {{-- RATIO SECTION --}}
                    {{-- ========================= --}}
                    <div id="ratio-section">

                        <div class="card bg-light mb-4">

                            <div class="card-body">

                                <h4 class="mb-3">
                                    Formula Rasio
                                </h4>

                                <div class="alert alert-info">
                                    Formula:
                                    <strong>Pembilang / Penyebut</strong>
                                </div>

                                <div class="row">

                                    <div class="col-md-6 mb-3">

                                        <label class="form-label">
                                            Label Pembilang
                                        </label>

                                        <input type="text" name="label_1" class="form-control"
                                            value="{{ old('label_1', $indicatorTemplate->label_1) }}"
                                            placeholder="Contoh: Jumlah Lulusan Tepat Waktu">

                                        <x-input-error :messages="$errors->get('label_1')" />

                                    </div>

                                    <div class="col-md-6 mb-3">

                                        <label class="form-label">
                                            Label Penyebut
                                        </label>

                                        <input type="text" name="label_2" class="form-control"
                                            value="{{ old('label_2', $indicatorTemplate->label_2) }}"
                                            placeholder="Contoh: Total Lulusan">

                                        <x-input-error :messages="$errors->get('label_2')" />

                                    </div>

                                </div>

                                <div>

                                    <label class="form-label">
                                        Target
                                    </label>

                                    <input type="number" name="target" step="0.01" min="0" class="form-control"
                                        value="{{ old('target', $indicatorTemplate->target) }}">

                                </div>

                            </div>

                        </div>

                    </div>

                    <div id="gap-unit-wrapper" class="mb-4">

                        <label class="form-label">
                            Satuan Gap
                        </label>

                        <input type="text" name="gap_unit" class="form-control"
                            value="{{ old('gap_unit', $indicatorTemplate->gap_unit) }}" placeholder="Contoh: Mahasiswa">

                        <div class="form-hint">
                            Digunakan untuk menampilkan selisih target.
                        </div>

                        <x-input-error :messages="$errors->get('gap_unit')" />

                    </div>

                    {{-- STRATEGIC REASON --}}
                    <div class="mb-3">

                        <label class="form-label">
                            Alasan Strategis
                        </label>

                        <textarea name="strategic_reason" rows="4" class="form-control">{{ old('strategic_reason', $indicatorTemplate->strategic_reason) }}</textarea>

                        <x-input-error :messages="$errors->get('strategic_reason')" />

                    </div>

                </div>

                {{-- FOOTER --}}
                <div class="card-footer text-end">

                    <button type="submit" class="btn btn-primary">
                        Simpan
                    </button>

                </div>

            </form>

        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            const formulaType = document.getElementById('formula_type');
            const percentageSection = document.getElementById('percentage-section');
            const ratioSection = document.getElementById('ratio-section');
            const gapUnitWrapper = document.getElementById('gap-unit-wrapper');
            const gapUnitInput = gapUnitWrapper.querySelector('input');

            function toggleType() {

                const percentageInputs = percentageSection.querySelectorAll('input, textarea, select');
                const ratioInputs = ratioSection.querySelectorAll('input, textarea, select');

                if (formulaType.value === 'percentage') {

                    percentageSection.style.display = 'block';
                    ratioSection.style.display = 'none';

                    percentageInputs.forEach(input => { input.disabled = false; });
                    ratioInputs.forEach(input => { input.disabled = true; });

                } else {

                    percentageSection.style.display = 'none';
                    ratioSection.style.display = 'block';

                    percentageInputs.forEach(input => { input.disabled = true; });
                    ratioInputs.forEach(input => { input.disabled = false; });

                }

                // gap unit selalu aktif
                gapUnitWrapper.style.display = 'block';
                gapUnitInput.disabled = false;
            }

            formulaType.addEventListener('change', toggleType);
            toggleType();

        });
    </script>
@endsection
