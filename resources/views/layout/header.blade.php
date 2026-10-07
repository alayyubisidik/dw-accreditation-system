@php
    $user = user();
    $dashboardMode = session('admin_dashboard_mode', 'university');
@endphp

<header class="navbar navbar-expand-md d-none d-lg-flex d-print-none">
    <div class="container-xl">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu"
            aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        {{-- Dashboard Mode Toggle (Admin Only) --}}
        <div class="navbar-nav me-auto">
            @if ($user->role == 'admin')
                <div class="nav-item">
                    <div class="btn-group" role="group" aria-label="Dashboard mode">
                        <form method="POST" action="{{ route('dashboard-admin.switch-mode') }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="mode" value="university">
                            <button type="submit"
                                class="btn btn-sm {{ $dashboardMode === 'university' ? 'btn-primary' : 'btn-outline-secondary' }}"
                                title="Dashboard Universitas">
                                <i class="ti ti-building me-1"></i>
                                Universitas
                            </button>
                        </form>
                        <form method="POST" action="{{ route('dashboard-admin.switch-mode') }}" class="d-inline">
                            @csrf
                            <input type="hidden" name="mode" value="study-program">
                            <button type="submit"
                                class="btn btn-sm {{ $dashboardMode === 'study-program' ? 'btn-primary' : 'btn-outline-secondary' }}"
                                title="Dashboard Program Studi">
                                <i class="ti ti-school me-1"></i>
                                Program Studi
                            </button>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <div class="navbar-nav flex-row order-md-last">
            <div class="nav-item dropdown">
                <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown"
                    aria-label="Open user menu">
                    <span class="avatar avatar-sm" style="background-image: url({{ asset($user->avatar) }})"></span>

                    <div class="d-none d-xl-block ps-2">
                        <div>{{ $user->name }}</div>
                        <div class="mt-1 small text-secondary">{{ $user->role }}</div>
                    </div>
                </a>


                <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow dropdown-profile">

                    <a href="{{ route('profiles.edit') }}" onclick="" class="dropdown-item">
                        Profile
                    </a>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <a href="#" onclick="confirmLogout(event)" class="dropdown-item">
                            Logout
                        </a>
                    </form>
                </div>
            </div>
        </div>

        <div class="collapse navbar-collapse" id="navbar-menu">

        </div>
    </div>
</header>
