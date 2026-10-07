@extends('layout.app')

@section('content')

<div class="container-xl" style="min-height:72vh;">

    <div class="card">

        <div class="card-header">

            <h3 class="card-title">
                Manajemen Pengguna
            </h3>

            <div class="card-actions">

                <form action="{{ route('dashboard-admin.master.users.index') }}"
                      method="GET"
                      class="d-flex gap-2">

                    <input type="text"
                           name="search"
                           class="form-control"
                           placeholder="Cari nama atau email..."
                           value="{{ request('search') }}"
                           style="width:250px">

                    <select name="role"
                            class="form-control"
                            style="width:180px">

                        <option value="">
                            Semua Role
                        </option>

                        <option value="admin"
                            {{ request('role') == 'admin' ? 'selected' : '' }}>
                            Admin
                        </option>

                        <option value="operator"
                            {{ request('role') == 'operator' ? 'selected' : '' }}>
                            Operator
                        </option>


                        </option>

                    </select>

                    <select name="status"
                            class="form-control"
                            style="width:180px">

                        <option value="">
                            Semua Status
                        </option>

                        <option value="1"
                            {{ request('status') === '1' ? 'selected' : '' }}>
                            Aktif
                        </option>

                        <option value="0"
                            {{ request('status') === '0' ? 'selected' : '' }}>
                            Nonaktif
                        </option>

                    </select>

                    <button class="btn btn-primary">
                        <i class="ti ti-search"></i>
                    </button>

                    @if(
                        request()->filled('search') ||
                        request()->filled('role') ||
                        request()->filled('status')
                    )

                        <a href="{{ route('dashboard-admin.master.users.index') }}"
                           class="btn btn-secondary">

                            Reset

                        </a>

                    @endif

                </form>

            </div>

            <div class="card-actions ms-2">

                <a href="{{ route('dashboard-admin.master.users.create') }}"
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

                            <th width="60">
                                No.
                            </th>

                            <th width="90">
                                Avatar
                            </th>

                            <th>
                                Nama
                            </th>

                            <th>
                                Email
                            </th>

                            <th>
                                Fakultas
                            </th>

                            <th>
                                Program Studi
                            </th>

                            <th>
                                Role
                            </th>

                            <th width="160">
                                Status
                            </th>

                            <th>
                                Dibuat Pada
                            </th>

                            <th width="100">
                                Aksi
                            </th>

                        </tr>

                    </thead>

                    <tbody>

                        @forelse($users as $user)

                            <tr>

                                <td>
                                    {{ $loop->iteration }}
                                </td>

                                <td>

                                    <img
                                        src="{{ asset($user->avatar) }}"
                                        alt="Avatar"
                                        style="
                                            width:60px;
                                            height:60px;
                                            object-fit:cover;
                                            border-radius:6px;
                                        "
                                    >

                                </td>

                                <td>
                                    {{ $user->name }}
                                </td>

                                <td>
                                    {{ $user->email }}
                                </td>

                                <td>

                                    {{ $user->faculty?->name ?? '-' }}

                                </td>

                                <td>

                                    {{ $user->studyProgram?->name ?? '-' }}

                                </td>

                                <td>

                                    @php

                                        $roles = [

                                            'admin' => 'Admin',
                                            'operator' => 'Operator',
                                            'reviewer' => 'Reviewer',

                                        ];

                                    @endphp

                                    <span class="badge bg-blue-lt">

                                        {{ $roles[$user->role] ?? $user->role }}

                                    </span>

                                </td>

                                <td>

                                    <form action="{{ route('dashboard-admin.master.users.toggle-status', $user) }}"
                                          method="POST">

                                        @csrf
                                        @method('PATCH')

                                        <select
                                            name="is_active"
                                            class="form-select form-select-sm status-select">

                                            <option value="1"
                                                {{ $user->is_active ? 'selected' : '' }}>
                                                Aktif
                                            </option>

                                            <option value="0"
                                                {{ !$user->is_active ? 'selected' : '' }}>
                                                Nonaktif
                                            </option>

                                        </select>

                                    </form>

                                </td>

                                <td>

                                    {{ $user->created_at->format('d-m-Y H:i') }}

                                </td>

                                <td>

                                    <div class="d-flex gap-2">

                                        <a href="{{ route('dashboard-admin.master.users.edit', $user) }}">
                                            <i class="ti ti-edit"></i>
                                        </a>

                                        <form action="{{ route('dashboard-admin.master.users.destroy', $user) }}"
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

                                <td colspan="10"
                                    class="text-center">

                                    Data tidak tersedia

                                </td>

                            </tr>

                        @endforelse

                    </tbody>

                </table>

            </div>

            <div class="card-footer">

                Total Pengguna :
                <strong>
                    {{ $users->count() }}
                </strong>

            </div>

        </div>

    </div>

</div>

@endsection

@push('scripts')

<script>

document.querySelectorAll('.status-select').forEach(function(select){

    select.addEventListener('change', function(){

        this.closest('form').submit();

    });

});

</script>

@endpush
