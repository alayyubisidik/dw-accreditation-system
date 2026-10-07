@extends('layout.app')

@section('content')
    <div class="container-xl" style="min-height: 72vh;">

        <div class="card">

            <div class="card-header">

                <div>
                    <h3 class="card-title">
                        Template Indikator
                    </h3>

                    <div class="text-secondary">
                        {{ $accreditationAgency->name }}
                    </div>
                </div>

                <div class="card-actions">

                    <form action="{{ route('dashboard-admin.study-program.accreditation-agencies.indicator-templates.index', $accreditationAgency) }}"
                        method="GET" class="d-flex gap-2">

                        <input type="text"
                            name="search"
                            class="form-control"
                            placeholder="Cari template indikator..."
                            value="{{ request('search') }}"
                            style="width: 250px;">

                        <button class="btn btn-outline-primary">
                            <i class="ti ti-search"></i>
                        </button>

                        @if (request()->filled('search'))
                            <a href="{{ route('dashboard-admin.study-program.accreditation-agencies.indicator-templates.index', $accreditationAgency) }}"
                                class="btn btn-outline-secondary">
                                <i class="ti ti-refresh"></i>
                                Reset
                            </a>
                        @endif

                    </form>

                </div>

                <div class="card-actions ms-2">

                    <a href="{{ route('dashboard-admin.study-program.accreditation-agencies.index') }}"
                        class="btn btn-outline-secondary">
                        Kembali
                    </a>

                    <a href="{{ route('dashboard-admin.study-program.accreditation-agencies.indicator-templates.create', $accreditationAgency) }}"
                        class="btn btn-primary">
                        Tambah
                    </a>

                </div>

            </div>

            <div class="card-body p-0">

                <div class="table-responsive">

                    <table class="table table-vcenter card-table">

                        <thead>
                            <tr>
                                <th width="80">No.</th>
                                <th>Judul</th>
                                <th>Tipe Formula</th>
                                <th>Target</th>
                                <th>Dibuat Pada</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($indicatorTemplates as $indicatorTemplate)

                                <tr>

                                    <td>{{ $loop->iteration }}</td>

                                    <td>{{ $indicatorTemplate->title }}</td>

                                    <td>
                                        @if ($indicatorTemplate->formula_type == 'percentage')
                                            Persentase
                                        @else
                                            Absolut
                                        @endif
                                    </td>

                                    <td>{{ $indicatorTemplate->target }}</td>


                                    <td>{{ $indicatorTemplate->created_at->format('d-m-Y H:i') }}</td>

                                    <td>

                                        <div class="d-flex gap-2">

                                            <a href="{{ route('dashboard-admin.study-program.accreditation-agencies.indicator-templates.edit', [$accreditationAgency, $indicatorTemplate]) }}">
                                                <i class="ti ti-edit"></i>
                                            </a>

                                            <form action="{{ route('dashboard-admin.study-program.accreditation-agencies.indicator-templates.destroy', [$accreditationAgency, $indicatorTemplate]) }}"
                                                method="POST">

                                                @csrf
                                                @method('DELETE')

                                                <a class="text-danger delete-btn">
                                                    <i class="ti ti-trash"></i>
                                                </a>

                                            </form>

                                        </div>

                                    </td>

                                </tr>

                            @empty

                                <tr>
                                    <td colspan="7" class="text-center">
                                        Data tidak tersedia
                                    </td>
                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="card-footer">
                    Total Template Indikator:
                    <strong>{{ $indicatorTemplates->count() }}</strong>
                </div>

            </div>

        </div>

    </div>
@endsection