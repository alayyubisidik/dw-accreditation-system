@extends('layout.app')

@section('content')

    <div class="container-xl">

        {{-- HEADER --}}
        <div class="mb-4">

            <h2 class="page-title">
                {{ $dashboardTitle }}
            </h2>

            <div class="text-secondary">

                @if (user()->role === 'admin')

                    Ringkasan capaian akreditasi seluruh universitas.

                @elseif(user()->role === 'reviewer')

                    Ringkasan capaian akreditasi fakultas.

                @else

                    Ringkasan capaian akreditasi program studi.

                @endif

            </div>

        </div>

        {{-- STATISTIK --}}
        <div class="row row-cards mb-4">

            @if (user()->role === 'admin')

                <div class="col-sm-6 col-lg-2">

                    <div class="card">

                        <div class="card-body text-center">

                            <div class="h1 mb-1">
                                {{ $totalFaculties }}
                            </div>

                            <div class="text-secondary">
                                Fakultas
                            </div>

                        </div>

                    </div>

                </div>

            @endif

            @if (user()->role !== 'operator')
                <div class="col-sm-6 col-lg-2">

                    <div class="card">

                        <div class="card-body text-center">

                            <div class="h1 mb-1">
                                {{ $totalStudyPrograms }}
                            </div>

                            <div class="text-secondary">
                                Prodi
                            </div>

                        </div>

                    </div>

                </div>

            @endif

            <div class="col-sm-6 col-lg-2">

                <div class="card">

                    <div class="card-body text-center">

                        <div class="h1 mb-1">
                            {{ $totalAccreditations }}
                        </div>

                        <div class="text-secondary">
                            Akreditasi
                        </div>

                    </div>

                </div>

            </div>

            <div class="col-sm-6 col-lg-2">

                <div class="card">

                    <div class="card-body text-center">

                        <div class="h1 mb-1">
                            {{ $totalIndicators }}
                        </div>

                        <div class="text-secondary">
                            Indikator
                        </div>

                    </div>

                </div>

            </div>

            <div class="col-sm-6 col-lg-2">

                <div class="card">

                    <div class="card-body text-center">

                        <div class="h1 mb-1 text-success">
                            {{ $achievedIndicators }}
                        </div>

                        <div class="text-secondary">
                            Tercapai
                        </div>

                    </div>

                </div>

            </div>

            <div class="col-sm-6 col-lg-2">

                <div class="card">

                    <div class="card-body text-center">

                        <div class="h1 mb-1 text-danger">
                            {{ $unachievedIndicators }}
                        </div>

                        <div class="text-secondary">
                            Belum Tercapai
                        </div>

                    </div>

                </div>

            </div>

            <div class="col-sm-6 col-lg-2">

                <div class="card">

                    <div class="card-body text-center">

                        <div class="h1 mb-1 text-warning">
                            {{ $incompleteIndicators }}
                        </div>

                        <div class="text-secondary">
                            Belum Diisi
                        </div>

                    </div>

                </div>

            </div>

        </div>

        {{-- PROGRESS PRODI --}}
        @if (user()->role !== 'operator')

            <div class="card mb-4">

                <div class="card-header">

                    <h3 class="card-title">
                        Progress Akreditasi per Prodi
                    </h3>

                </div>

                <div class="card-body">

                    @forelse($programProgress as $program)

                        <div class="mb-4">

                            <div class="d-flex justify-content-between mb-1">

                                <span>

                                    {{ $program->name }}

                                </span>

                                <strong>

                                    {{ $program->progress }}%

                                </strong>

                            </div>

                            <div class="progress">

                                <div class="progress-bar bg-success"
                                    style="width: {{ $program->progress }}%">
                                </div>

                            </div>

                            <div class="small text-secondary mt-1">

                                {{ $program->achieved }}
                                dari
                                {{ $program->total }}
                                indikator tercapai

                            </div>

                        </div>

                    @empty

                        <div class="empty">

                            <div class="empty-icon">
                                <i class="ti ti-chart-bar-off"></i>
                            </div>

                            <p class="empty-title">
                                Tidak ada data
                            </p>

                        </div>

                    @endforelse

                </div>

            </div>

        @endif

        {{-- RANKING --}}
        @if (user()->role !== 'operator')

            <div class="card">

                <div class="card-header">

                    <h3 class="card-title">
                        Ranking Prodi
                    </h3>

                </div>

                <div class="table-responsive">

                    <table class="table table-vcenter card-table">

                        <thead>

                            <tr>

                                <th width="80">
                                    Rank
                                </th>

                                <th>
                                    Program Studi
                                </th>

                                <th width="150">
                                    Progress
                                </th>

                                <th width="150">
                                    Tercapai
                                </th>

                            </tr>

                        </thead>

                        <tbody>

                            @forelse($rankingPrograms as $program)

                                <tr>

                                    <td>

                                        @if ($loop->iteration === 1)

                                            🥇

                                        @elseif($loop->iteration === 2)

                                            🥈

                                        @elseif($loop->iteration === 3)

                                            🥉

                                        @else

                                            #{{ $loop->iteration }}

                                        @endif

                                    </td>

                                    <td>

                                        {{ $program->name }}

                                    </td>

                                    <td>

                                        <span class="badge bg-primary text-white">

                                            {{ $program->progress }}%

                                        </span>

                                    </td>

                                    <td>

                                        {{ $program->achieved }}
                                        /
                                        {{ $program->total }}

                                    </td>

                                </tr>

                            @empty

                                <tr>

                                    <td colspan="4" class="text-center py-5">

                                        <div class="text-secondary">
                                            Tidak ada data
                                        </div>

                                    </td>

                                </tr>

                            @endforelse

                        </tbody>

                    </table>

                </div>

            </div>

        @endif

    </div>

@endsection
