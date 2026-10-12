@extends('layout.app')

@section('content')
    <div class="container-xl">
        <div class="card" style="max-width:620px; margin:auto; border-radius:10px;">
            <div class="card-header">
                <h3 class="card-title">Perbandingan Batch Akreditasi Program Studi</h3>
            </div>
            <div class="card-body">
                <p class="text-secondary mb-4">
                    Pilih program studi dan dua batch akreditasi yang ingin dibandingkan.
                </p>
                <form action="{{ route('dashboard-admin.study-program.accreditations.compare') }}" method="GET">
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Program Studi <span class="text-danger">*</span></label>
                        <select name="study_program_id" class="form-select" required>
                            <option value="">-- Pilih Program Studi --</option>
                            @foreach ($studyPrograms as $studyProgram)
                                <option value="{{ $studyProgram->id }}" @selected(old('study_program_id', request('study_program_id')) == $studyProgram->id)>
                                    {{ $studyProgram->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('study_program_id')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label fw-semibold">Batch A <span class="text-danger">*</span></label>
                        <select name="batch_a" class="form-select" required id="select-batch-a">
                            <option value="">-- Pilih Batch A --</option>
                            @foreach ($batches as $batch)
                                <option value="{{ $batch->id }}" data-agency="{{ $batch->study_program_accreditation_agency_id }}" @selected(old('batch_a') == $batch->id)>
                                    {{ $batch->name }} ({{ $batch->year }}) — {{ $batch->agency->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('batch_a')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="form-label fw-semibold">Batch B <span class="text-danger">*</span></label>
                        <select name="batch_b" class="form-select" required id="select-batch-b">
                            <option value="">-- Pilih Batch B dulu pilih Batch A --</option>
                            @foreach ($batches as $batch)
                                <option value="{{ $batch->id }}" data-agency="{{ $batch->study_program_accreditation_agency_id }}" @selected(old('batch_b') == $batch->id)>
                                    {{ $batch->name }} ({{ $batch->year }}) — {{ $batch->agency->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('batch_b')
                            <div class="form-text text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-arrows-diff me-1"></i> Bandingkan
                        </button>
                        <a href="{{ route('dashboard-admin.study-program.accreditations.index') }}" class="btn btn-outline-secondary">Kembali</a>
                    </div>
                </form>
            </div>
            @if ($batches->count() < 2 || $studyPrograms->isEmpty())
                <div class="card-footer">
                    <div class="empty">
                        <p class="empty-title">Belum cukup data</p>
                        <p class="empty-subtitle text-secondary">Dibutuhkan minimal 2 batch akreditasi dan data program studi untuk dibandingkan.</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
@endsection

@push('scripts')
<script>
    $(function () {
        const $selectA = $('#select-batch-a');
        const $selectB = $('#select-batch-b');
        const allOptionsB = $selectB.find('option').not(':first').clone();

        function filterB() {
            const agencyId = $selectA.find('option:selected').data('agency');
            const oldVal = $selectB.val();

            $selectB.find('option').not(':first').remove();

            if (!agencyId) {
                $selectB.find('option:first').text('-- Pilih Batch B (pilih Batch A dulu) --');
                return;
            }

            $selectB.find('option:first').text('-- Pilih Batch B --');
            const selectedBatchA = $selectA.val();

            allOptionsB.each(function () {
                if ($(this).data('agency') == agencyId && $(this).val() != selectedBatchA) {
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
