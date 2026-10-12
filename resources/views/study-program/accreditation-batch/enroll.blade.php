@extends('layout.app')

@section('content')
    <div class="container-xl">

        <div class="card">

            {{-- HEADER --}}
            <div class="card-header">

                <div>
                    <h3 class="card-title">
                        Tambah Prodi ke Batch
                    </h3>
                    <div class="text-secondary">
                        <strong>{{ $batch->name }}</strong>
                        &mdash; {{ $batch->agency->name ?? '-' }} &mdash; {{ $batch->year }}
                    </div>
                </div>

                <div class="card-actions">
                    <a href="{{ route('dashboard-admin.study-program.accreditation-batches.index') }}"
                        class="btn btn-secondary">
                        <i class="ti ti-arrow-left me-1"></i>
                        Kembali
                    </a>
                </div>

            </div>

            <form action="{{ route('dashboard-admin.study-program.accreditation-batches.enroll', $batch) }}"
                method="POST">

                @csrf

                <div class="card-body">

                    {{-- INFO ENROLLED --}}
                    @if ($enrolledIds->isNotEmpty())
                        <div class="alert alert-info mb-3">
                            <div class="d-flex align-items-center">
                                <i class="ti ti-info-circle me-2 fs-4"></i>
                                <div>
                                    <strong>{{ $enrolledIds->count() }} program studi</strong> sudah terdaftar di batch ini
                                    (ditampilkan abu-abu, tidak bisa dipilih ulang).
                                </div>
                            </div>
                        </div>
                    @endif

                    {{-- VALIDATION ERROR --}}
                    @if ($errors->has('study_program_ids') || $errors->has('study_program_ids.*'))
                        <div class="alert alert-danger mb-3">
                            <i class="ti ti-alert-circle me-2"></i>
                            {{ $errors->first('study_program_ids') ?: $errors->first('study_program_ids.*') }}
                        </div>
                    @endif

                    {{-- TOOLBAR --}}
                    <div class="d-flex align-items-center justify-content-between mb-3 gap-2 flex-wrap">

                        {{-- Search --}}
                        <div class="input-group" style="max-width: 320px;">
                            <span class="input-group-text">
                                <i class="ti ti-search"></i>
                            </span>
                            <input type="text" id="sp-search" class="form-control"
                                placeholder="Cari program studi...">
                        </div>

                        {{-- Select all / deselect --}}
                        <div class="d-flex gap-2">
                            <button type="button" id="btn-select-all" class="btn btn-sm btn-outline-primary">
                                <i class="ti ti-checks me-1"></i>
                                Pilih Semua
                            </button>
                            <button type="button" id="btn-deselect-all" class="btn btn-sm btn-outline-secondary">
                                <i class="ti ti-square me-1"></i>
                                Batal Pilih
                            </button>
                        </div>

                    </div>

                    {{-- PROGRAM STUDI LIST --}}
                    @php
                        $grouped = $studyPrograms->groupBy(fn($sp) => $sp->faculty->name ?? 'Tanpa Fakultas');
                    @endphp

                    @forelse ($grouped as $facultyName => $programs)

                        <div class="mb-3 sp-faculty-group">

                            {{-- Faculty header --}}
                            <div class="subheader mb-2 sp-faculty-label">
                                <i class="ti ti-building me-1"></i>
                                {{ $facultyName }}
                            </div>

                            <div class="row g-2">

                                @foreach ($programs as $sp)
                                    @php $enrolled = $enrolledIds->contains($sp->id); @endphp

                                    <div class="col-12 col-sm-6 col-lg-4 sp-item-col"
                                        data-name="{{ strtolower($sp->name) }}"
                                        data-faculty="{{ strtolower($facultyName) }}">

                                        <label class="form-check p-0 m-0 h-100"
                                            style="cursor: {{ $enrolled ? 'default' : 'pointer' }};">

                                            <div class="card mb-0 h-100
                                                @if ($enrolled) border-0 bg-light @endif
                                                @if (!$enrolled) sp-card-selectable @endif"
                                                style="transition: border-color .15s, box-shadow .15s;">

                                                <div class="card-body d-flex align-items-center gap-3 py-3">

                                                    <input
                                                        class="form-check-input sp-checkbox m-0 flex-shrink-0"
                                                        type="checkbox"
                                                        name="study_program_ids[]"
                                                        value="{{ $sp->id }}"
                                                        @if ($enrolled) disabled checked @endif
                                                        style="width: 1.2rem; height: 1.2rem;">

                                                    <div class="flex-grow-1 min-w-0">
                                                        <div class="fw-medium text-truncate">
                                                            {{ $sp->name }}
                                                        </div>
                                                        <div class="text-secondary small">
                                                            {{ $sp->faculty->name ?? '-' }}
                                                        </div>
                                                    </div>

                                                    @if ($enrolled)
                                                        <span class="badge bg-success text-white d-inline-flex align-items-center gap-1 flex-shrink-0">
                                                            <i class="ti ti-check"></i>
                                                            <span>Terdaftar</span>
                                                        </span>
                                                    @endif

                                                </div>

                                            </div>

                                        </label>

                                    </div>

                                @endforeach

                            </div>

                        </div>

                    @empty

                        <div class="text-center py-5 text-secondary">
                            <i class="ti ti-school-off" style="font-size: 3rem;"></i>
                            <div class="mt-2">Belum ada program studi yang terdaftar.</div>
                        </div>

                    @endforelse

                </div>

                {{-- FOOTER --}}
                <div class="card-footer d-flex align-items-center justify-content-between">

                    <div class="text-secondary" id="selected-count-label">
                        0 prodi dipilih
                    </div>

                    <button type="submit" class="btn btn-primary" id="btn-submit">
                        <i class="ti ti-user-plus me-1"></i>
                        Daftarkan Prodi Terpilih
                    </button>

                </div>

            </form>

        </div>

    </div>
@endsection

@push('scripts')
<script>
    $(function () {

        const $checkboxes = $('.sp-checkbox:not(:disabled)');
        const $countLabel = $('#selected-count-label');
        const $btnSubmit  = $('#btn-submit');

        // Update label jumlah terpilih
        function updateCount() {
            const count = $checkboxes.filter(':checked').length;
            $countLabel.text(count + ' prodi dipilih');
            $btnSubmit.prop('disabled', count === 0);
        }

        updateCount();
        $checkboxes.on('change', updateCount);

        // Highlight card saat checkbox berubah
        $checkboxes.on('change', function () {
            const $card = $(this).closest('.card');
            if ($(this).is(':checked')) {
                $card.addClass('border-primary shadow-sm');
            } else {
                $card.removeClass('border-primary shadow-sm');
            }
        });

        // Select all / deselect
        $('#btn-select-all').on('click', function () {
            $checkboxes.filter(':visible').prop('checked', true).trigger('change');
        });

        $('#btn-deselect-all').on('click', function () {
            $checkboxes.filter(':visible').prop('checked', false).trigger('change');
        });

        // Search filter
        $('#sp-search').on('input', function () {
            const term = $(this).val().toLowerCase().trim();

            $('.sp-item-col').each(function () {
                const name    = $(this).data('name') || '';
                const faculty = $(this).data('faculty') || '';
                const match   = !term || name.includes(term) || faculty.includes(term);
                $(this).toggle(match);
            });

            // Sembunyikan header fakultas jika semua anaknya hidden
            $('.sp-faculty-group').each(function () {
                const anyVisible = $(this).find('.sp-item-col:visible').length > 0;
                $(this).toggle(anyVisible);
            });
        });

        // Clickable card (klik area card selain checkbox)
        $(document).on('click', '.sp-card-selectable', function (e) {
            if ($(e.target).is('input[type=checkbox]')) return;
            const $cb = $(this).find('.sp-checkbox');
            $cb.prop('checked', !$cb.prop('checked')).trigger('change');
        });

    });
</script>

<style>
    .sp-card-selectable:hover {
        border-color: #206bc4 !important;
        box-shadow: 0 0 0 2px rgba(32, 107, 196, .15);
    }

    .card .border-primary {
        border-color: #206bc4 !important;
    }
</style>
@endpush
