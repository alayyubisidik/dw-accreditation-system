@extends('layout.app')

@section('content')
    <div class="container-xl" style="min-height: 72vh;">

        <div class="card">

            <div class="card-header">

                <h3 class="card-title">
                    Manajemen Fakultas
                </h3>

                <div class="card-actions">

                    <form action="{{ route('dashboard-admin.master.faculties.index') }}" method="GET" class="d-flex gap-2">

                        <input type="text" name="search" class="form-control" placeholder="Cari fakultas..."
                            value="{{ request('search') }}" style="width: 250px;">

                        <button class="btn btn-outline-primary">
                            <i class="ti ti-search"></i>
                        </button>

                        @if (request()->filled('search'))
                            <a href="{{ route('dashboard-admin.master.faculties.index') }}" class="btn btn-outline-secondary">
                                <i class="ti ti-refresh"></i>
                                Reset
                            </a>
                        @endif

                    </form>

                </div>

                <div class="card-actions ms-2">

                    <a href="{{ route('dashboard-admin.master.faculties.create') }}" class="btn btn-primary">
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
                                <th>Nama Fakultas</th>
                                <th>Dibuat Pada</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse ($faculties as $faculty)
                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>
                                        {{ $faculty->name }}
                                    </td>

                                    <td>
                                        {{ $faculty->created_at->format('d-m-Y H:i') }}
                                    </td>

                                    <td>

                                        <div class="d-flex gap-2">

                                            <a href="{{ route('dashboard-admin.master.faculties.edit', $faculty) }}">
                                                <i class="ti ti-edit"></i>
                                            </a>

                                            <form action="{{ route('dashboard-admin.master.faculties.destroy', $faculty) }}"
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
                                    <td colspan="4" class="text-center">
                                        Data tidak tersedia
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="card-footer">
                    Total Fakultas:
                    <strong>{{ $faculties->count() }}</strong>
                </div>

            </div>

        </div>

    </div>
@endsection
