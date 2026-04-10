@extends('Layout.home')

@section('content')
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">

            <!-- HEADER -->
            <div class="mb-4">
                <h4 class="fw-bold">Dashboard Parkir</h4>
                <p class="text-muted">Monitoring aktivitas parkir secara real-time</p>
            </div>

            <!-- STATISTICS -->
            <div class="row g-4 mb-4">

                <!-- Parkir Aktif -->
                <div class="col-xl-4 col-md-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body d-flex justify-content-between align-items-center">

                            <div>
                                <span class="text-muted d-block mb-1">Parkir Aktif</span>
                                <h3 class="fw-bold mb-0">{{ $parkirAktif }}</h3>
                            </div>

                            <div class="avatar avatar-md bg-label-primary d-flex align-items-center justify-content-center">
                                <i class="ri-car-fill ri-lg"></i>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Transaksi -->
                <div class="col-xl-4 col-md-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body d-flex justify-content-between align-items-center">

                            <div>
                                <span class="text-muted d-block mb-1">Transaksi Hari Ini</span>
                                <h3 class="fw-bold mb-0">{{ $totalTransaksi }}</h3>
                            </div>

                            <div class="avatar avatar-md bg-label-success d-flex align-items-center justify-content-center">
                                <i class="ri-file-list-3-fill ri-lg"></i>
                            </div>

                        </div>
                    </div>
                </div>

                <!-- Pendapatan -->
                <div class="col-xl-4 col-md-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-body d-flex justify-content-between align-items-center">

                            <div>
                                <span class="text-muted d-block mb-1">Pendapatan</span>
                                <h3 class="fw-bold mb-0">
                                    Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}
                                </h3>
                            </div>

                            <div class="avatar avatar-md bg-label-warning d-flex align-items-center justify-content-center">
                                <i class="ri-money-dollar-circle-fill ri-lg"></i>
                            </div>

                        </div>
                    </div>
                </div>

            </div>

            <div class="row g-4">

                <!-- AREA PARKIR -->
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-header">
                            <h5 class="mb-0">Area Parkir</h5>
                        </div>
                        <div class="card-body">

                            @foreach ($areas as $area)
                                @php
                                    $persen = $area->kapasitas > 0 ? ($area->terisi / $area->kapasitas) * 100 : 0;
                                @endphp

                                <div class="mb-3">
                                    <div class="d-flex justify-content-between">
                                        <span class="fw-medium">{{ $area->nama_area }}</span>
                                        <span class="text-muted">
                                            {{ $area->terisi }}/{{ $area->kapasitas }}
                                        </span>
                                    </div>

                                    <div class="progress mt-1" style="height:6px;">
                                        <div class="progress-bar bg-primary" style="width: {{ $persen }}%">
                                        </div>
                                    </div>
                                </div>
                            @endforeach

                        </div>
                    </div>
                </div>

                <!-- LOG AKTIVITAS -->
                <div class="col-lg-6">
                    <div class="card shadow-sm border-0">
                        <div class="card-header">
                            <h5 class="mb-0">Log Aktivitas</h5>
                        </div>
                        <div class="card-body">

                            @forelse($logs as $log)
                                <div class="d-flex align-items-start mb-3">
                                    <div class="avatar avatar-sm bg-label-info me-3">
                                        <i class="ri-user-3-line"></i>
                                    </div>
                                    <div>
                                        <span class="fw-medium">
                                            {{ $log->user->username ?? '-' }}
                                        </span>
                                        <p class="mb-0 text-muted small">
                                            {{ $log->aktivitas }}
                                        </p>
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($log->waktu_aktivitas)->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            @empty
                                <p class="text-muted">Belum ada aktivitas</p>
                            @endforelse

                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    
@endsection
