@php
    $user = user();
    $dashboardMode = session('admin_dashboard_mode', 'university');
@endphp
<aside class="navbar navbar-vertical navbar-expand-lg d-print-none" data-bs-theme="dark">
    <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu"
            aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="d-flex gap-3 pt-3 px-3 pb-3 pb-lg-0">
            <div>
                <img src="{{ asset('images/udw-logo.png') }}" width="43" alt="">
            </div>
            <div>
                <p class="mb-1 fw-semibold">DW</p>
                <p class="mb-0 small">Accreditation System</p>
            </div>
        </div>

        <div class="navbar-nav flex-row d-lg-none">
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                    aria-label="Open user menu">
                    <span class="avatar avatar-sm" style="background-image: url({{ asset($user->avatar ?? 'images/default-avatar.png') }})"></span>
                    <div class="d-none d-xl-block ps-2">
                        <div>{{ $user->name }}</div>
                        <div class="mt-1 small text-secondary">{{ $user->role }}</div>
                    </div>
                </a>
                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                    <a href="{{ route('profiles.edit') }}" class="dropdown-item">Profile</a>
                    <div class="dropdown-divider"></div>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="dropdown-item">Logout</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="collapse navbar-collapse" id="sidebar-menu">
            <ul class="navbar-nav pt-lg-3 sidebar-menu">

                {{-- ===================== ADMIN ===================== --}}
                @if ($user->role == 'admin')

                    @if ($dashboardMode === 'university')
                        {{-- === University Dashboard === --}}
                        <li class="nav-item {{ setActive(['dashboard-admin.university.index*'], 'active') }}">
                            <a class="nav-link" href="{{ route('dashboard-admin.university.index') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-home"></i></span>
                                <span class="nav-link-title">Dashboard</span>
                            </a>
                        </li>

                        <li class="nav-item {{ setActive(['dashboard-admin.university.accreditation-agencies.*'], 'active') }}">
                            <a class="nav-link" href="{{ route('dashboard-admin.university.accreditation-agencies.index') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-building-bank"></i></span>
                                <span class="nav-link-title">Lembaga Akreditasi</span>
                            </a>
                        </li>

                        <li class="nav-item {{ setActive(['dashboard-admin.university.accreditation-batches.*'], 'active') }}">
                            <a class="nav-link" href="{{ route('dashboard-admin.university.accreditation-batches.index') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-calendar-event"></i></span>
                                <span class="nav-link-title">Batch Akreditasi</span>
                            </a>
                        </li>

                        <li class="nav-item {{ setActive(['dashboard-admin.university.accreditations.*'], 'active') }}">
                            <a class="nav-link" href="{{ route('dashboard-admin.university.accreditations.index') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-medal"></i></span>
                                <span class="nav-link-title">Akreditasi</span>
                            </a>
                        </li>

                    @else
                        {{-- === Study Program Dashboard === --}}
                        <li class="nav-item {{ setActive(['dashboard-admin.study-program.index*'], 'active') }}">
                            <a class="nav-link" href="{{ route('dashboard-admin.study-program.index') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-home"></i></span>
                                <span class="nav-link-title">Dashboard</span>
                            </a>
                        </li>

                        <li class="nav-item {{ setActive(['dashboard-admin.study-program.accreditation-agencies.*'], 'active') }}">
                            <a class="nav-link" href="{{ route('dashboard-admin.study-program.accreditation-agencies.index') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-building-bank"></i></span>
                                <span class="nav-link-title">Lembaga Akreditasi</span>
                            </a>
                        </li>

                        <li class="nav-item {{ setActive(['dashboard-admin.study-program.accreditation-batches.*'], 'active') }}">
                            <a class="nav-link" href="{{ route('dashboard-admin.study-program.accreditation-batches.index') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-calendar-event"></i></span>
                                <span class="nav-link-title">Batch Akreditasi</span>
                            </a>
                        </li>

                        <li class="nav-item {{ setActive(['dashboard-admin.study-program.accreditations.*'], 'active') }}">
                            <a class="nav-link" href="{{ route('dashboard-admin.study-program.accreditations.index') }}">
                                <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-medal"></i></span>
                                <span class="nav-link-title">Akreditasi</span>
                            </a>
                        </li>

                    @endif

                    {{-- === Master Data (selalu tampil untuk admin) === --}}
                    <li class="nav-item {{ setActive(['dashboard-admin.master.faculties.*'], 'active') }}">
                        <a class="nav-link" href="{{ route('dashboard-admin.master.faculties.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-school"></i></span>
                            <span class="nav-link-title">Fakultas</span>
                        </a>
                    </li>

                    <li class="nav-item {{ setActive(['dashboard-admin.master.study-programs.*'], 'active') }}">
                        <a class="nav-link" href="{{ route('dashboard-admin.master.study-programs.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-briefcase"></i></span>
                            <span class="nav-link-title">Program Studi</span>
                        </a>
                    </li>

                    <li class="nav-item {{ setActive(['dashboard-admin.master.users.*'], 'active') }}">
                        <a class="nav-link" href="{{ route('dashboard-admin.master.users.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-users"></i></span>
                            <span class="nav-link-title">Pengguna</span>
                        </a>
                    </li>

                @endif

                {{-- ===================== OPERATOR ===================== --}}
                @if ($user->role == 'operator')

                    <li class="nav-item {{ setActive(['dashboard-operator.index*'], 'active') }}">
                        <a class="nav-link" href="{{ route('dashboard-operator.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-home"></i></span>
                            <span class="nav-link-title">Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-item {{ setActive(['dashboard-operator.accreditations.*'], 'active') }}">
                        <a class="nav-link" href="{{ route('dashboard-operator.accreditations.index') }}">
                            <span class="nav-link-icon d-md-none d-lg-inline-block"><i class="ti ti-medal"></i></span>
                            <span class="nav-link-title">Akreditasi</span>
                        </a>
                    </li>

                @endif

            </ul>
        </div>
    </div>
</aside>