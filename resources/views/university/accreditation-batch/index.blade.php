@extends('layout.app')

@section('content')
    <div class="container-xl" style="min-height: 72vh;">

        <div class="card">

            <div class="card-header">

                <h3 class="card-title">
                    Manajemen Batch Akreditasi
                </h3>

                <div class="card-actions">

                    <form action="{{ route('dashboard-admin.university.accreditation-batches.index') }}" method="GET"
                        class="d-flex gap-2">

                        <input type="text" name="search" class="form-control"
                            placeholder="Cari batch akreditasi..."
                            value="{{ request('search') }}"
                            style="width: 250px;">

                        <button class="btn btn-outline-primary">
                            <i class="ti ti-search"></i>
                        </button>

                        @if (request()->filled('search'))
                            <a href="{{ route('dashboard-admin.university.accreditation-batches.index') }}"
                                class="btn btn-outline-secondary">
                                <i class="ti ti-refresh"></i>
                                Reset
                            </a>
                        @endif

                    </form>

                </div>

                <div class="card-actions ms-2">

                    <a href="{{ route('dashboard-admin.university.accreditation-batches.create') }}"
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
                                <th>Lembaga Akreditasi</th>
                                <th>Nama Batch</th>
                                <th width="100">Tahun</th>
                                <th width="120">Status</th>
                                <th>Dibuat Pada</th>
                                <th width="80">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($batches as $batch)

                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>
                                        {{ $batch->agency->name ?? '-' }}
                                    </td>

                                    <td>
                                        {{ $batch->name }}
                                    </td>

                                    <td>
                                        {{ $batch->year }}
                                    </td>

                                    <td>
                                        @if ($batch->is_active)
                                            <span class="badge bg-green-lt">Aktif</span>
                                        @else
                                            <span class="badge bg-secondary-lt">Nonaktif</span>
                                        @endif
                                    </td>

                                    <td>
                                        {{ $batch->created_at->format('d-m-Y H:i') }}
                                    </td>

                                    <td>

                                        <div class="d-flex gap-2">

                                            <a href="{{ route('dashboard-admin.university.accreditation-batches.edit', $batch) }}"
                                                class="text-primary">
                                                <i class="ti ti-pencil"></i>
                                            </a>

                                            <form
                                                action="{{ route('dashboard-admin.university.accreditation-batches.destroy', $batch) }}"
                                                method="POST">

                                                @csrf
                                                @method('DELETE')

                                                <a class="text-danger delete-btn-batch" style="cursor: pointer;">
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
                    Total Batch Akreditasi:
                    <strong>{{ $batches->count() }}</strong>
                </div>

            </div>

        </div>

    </div>

@endsection
