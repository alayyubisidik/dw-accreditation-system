@extends('layout.app')

@section('content')
    <div class="container-xl" style="min-height:72vh;">

        <div class="card">

            <div class="card-header">

                <h3 class="card-title">
                    Manajemen Program Studi
                </h3>

                <div class="card-actions">

                    <form action="{{ route('dashboard-admin.master.study-programs.index') }}" method="GET" class="d-flex gap-2">

                        <input type="text" name="search" class="form-control" placeholder="Cari program studi..."
                            value="{{ request('search') }}" style="width:250px">

                        <select name="faculty_id" class="form-control" style="width:220px">

                            <option value="">
                                Semua Fakultas
                            </option>

                            @foreach ($faculties as $faculty)
                                <option value="{{ $faculty->id }}"
                                    {{ request('faculty_id') == $faculty->id ? 'selected' : '' }}>

                                    {{ $faculty->name }}

                                </option>
                            @endforeach

                        </select>

                        <button class="btn btn-primary">
                            <i class="ti ti-search"></i>
                        </button>

                        @if (request()->filled('search') || request()->filled('faculty_id'))
                            <a href="{{ route('dashboard-admin.master.study-programs.index') }}" class="btn btn-secondary">

                                <i class="ti ti-refresh"></i>
                                Reset

                            </a>
                        @endif

                    </form>

                </div>

                <div class="card-actions ms-2">

                    <a href="{{ route('dashboard-admin.master.study-programs.create') }}" class="btn btn-primary">

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
                                <th>Program Studi</th>
                                <th>Fakultas</th>
                                <th>Dibuat Pada</th>
                                <th width="120">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>

                            @forelse($studyPrograms as $studyProgram)
                                <tr>

                                    <td>
                                        {{ $loop->iteration }}
                                    </td>

                                    <td>
                                        {{ $studyProgram->name }}
                                    </td>

                                    <td>
                                        <span class="badge bg-blue-lt">
                                            {{ $studyProgram->faculty?->name }}
                                        </span>
                                    </td>

                                    <td>
                                        {{ $studyProgram->created_at->format('d-m-Y H:i') }}
                                    </td>

                                    <td>

                                        <div class="d-flex gap-2">

                                            <a href="{{ route('dashboard-admin.master.study-programs.edit', $studyProgram) }}">
                                                <i class="ti ti-edit"></i>
                                            </a>

                                            <form
                                                action="{{ route('dashboard-admin.master.study-programs.destroy', $studyProgram) }}"
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
                                    <td colspan="5" class="text-center">
                                        Data tidak tersedia
                                    </td>
                                </tr>
                            @endforelse

                        </tbody>

                    </table>

                </div>

                <div class="card-footer">
                    Total Program Studi:
                    <strong>{{ $studyPrograms->count() }}</strong>
                </div>

            </div>

        </div>

    </div>
@endsection
