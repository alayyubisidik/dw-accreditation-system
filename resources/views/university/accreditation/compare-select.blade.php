@extends('layout.app')

@section('content')
    <div class="container-xl">

        <div class="card" style="max-width:620px; margin:auto; border-radius:10px;">

            <div class="card-header">
                <h3 class="card-title">Perbandingan Akreditasi Universitas</h3>
            </div>

            <div class="card-body">

                <p class="text-secondary mb-4">
                    Pilih dua akreditasi yang ingin dibandingkan. Perbandingan dilakukan
                    berdasarkan indikator yang memiliki template yang sama pada kedua akreditasi.
                </p>

                <form action="{{ route('dashboard-admin.university.accreditations.compare') }}" method="GET">

                    <div class="mb-3">
                        <label class="form-label fw-semibold">Akreditasi A <span class="text-danger">*</span></label>
                        <select name="accreditation_a" class="form-select" required id="select-accreditation-a">
                            <option value="">-- Pilih Akreditasi A --</option>
                            @foreach ($accreditations as $accreditation)
                                <option value="{{ $accreditation->id }}"
                                    data-agency="{{ $accreditation->university_accreditation_agency_id }}"
                                    @selected(old('accreditation_a') == $accreditation->id)>
                                    {{ $accreditation->name }} ({{ $accreditation->year }}) — {{ $accreditation->agency->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('accreditation_a')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="form-label fw-semibold">Akreditasi B <span class="text-danger">*</span></label>
                        <select name="accreditation_b" class="form-select" required id="select-accreditation-b">
                            <option value="">-- Pilih Akreditasi B dulu pilih Akreditasi A --</option>
                            @foreach ($accreditations as $accreditation)
                                <option value="{{ $accreditation->id }}"
                                    data-agency="{{ $accreditation->university_accreditation_agency_id }}"
                                    @selected(old('accreditation_b') == $accreditation->id)>
                                    {{ $accreditation->name }} ({{ $accreditation->year }}) — {{ $accreditation->agency->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('accreditation_b')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-arrows-diff me-1"></i> Bandingkan
                        </button>
                        <a href="{{ route('dashboard-admin.university.accreditations.index') }}" class="btn btn-outline-secondary">
                            Kembali
                        </a>
                    </div>

                </form>

            </div>

            @if ($accreditations->isEmpty())
                <div class="card-footer">
                    <div class="empty">
                        <p class="empty-title">Belum cukup data</p>
                        <p class="empty-subtitle text-secondary">
                            Dibutuhkan minimal 2 akreditasi yang sudah memiliki data untuk dibandingkan.
                        </p>
                    </div>
                </div>
            @endif

        </div>

    </div>
@endsection

@push('scripts')
<script>
    $(function () {
        const $selectA = $('#select-accreditation-a');
        const $selectB = $('#select-accreditation-b');
        const allOptionsB = $selectB.find('option').not(':first').clone();

        function filterB() {
            const agencyId = $selectA.find('option:selected').data('agency');
            const oldVal = $selectB.val();

            $selectB.find('option').not(':first').remove();

            if (!agencyId) {
                $selectB.find('option:first').text('-- Pilih Akreditasi B (pilih Akreditasi A dulu) --');
                return;
            }

            $selectB.find('option:first').text('-- Pilih Akreditasi B --');

            const selectedAccreditationA = $selectA.val();

            allOptionsB.each(function () {
                if ($(this).data('agency') == agencyId && $(this).val() != selectedAccreditationA) {
                    $selectB.append($(this).clone());
                }
            });

            $selectB.val(oldVal);
        }

        $selectA.on('change', filterB);
        filterB();
    });
</script>
@endpush
