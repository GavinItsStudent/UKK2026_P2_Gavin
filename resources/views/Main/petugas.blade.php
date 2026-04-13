@extends('Layout.home')

@section('content')
    <div class="container-xxl container-p-y">

        <!-- HEADER -->
        <div class="mb-4">
            <h4 class="fw-bold mb-1">Dashboard Petugas</h4>
            <small class="text-muted">Monitoring aktivitas parkir hari ini</small>
        </div>

        <!-- STAT -->
        <div class="row g-4 mb-4">

            <!-- PARKIR AKTIF -->
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted d-block">Parkir Aktif</small>
                            <h3 class="fw-bold mb-0">{{ $parkirAktif }}</h3>
                        </div>
                        <div
                            class="avatar avatar-md bg-label-primary d-flex align-items-center justify-content-center rounded">
                            <i class="ri-car-fill ri-lg"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TRANSAKSI -->
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted d-block">Transaksi Hari Ini</small>
                            <h3 class="fw-bold mb-0">{{ $totalHariIni }}</h3>
                        </div>
                        <div
                            class="avatar avatar-md bg-label-success d-flex align-items-center justify-content-center rounded">
                            <i class="ri-file-list-3-fill ri-lg"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- PENDAPATAN -->
            <div class="col-md-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <div>
                            <small class="text-muted d-block">Pendapatan</small>
                            <h3 class="fw-bold mb-0">
                                Rp {{ number_format($pendapatan, 0, ',', '.') }}
                            </h3>
                        </div>
                        <div
                            class="avatar avatar-md bg-label-warning d-flex align-items-center justify-content-center rounded">
                            <i class="ri-money-dollar-circle-fill ri-lg"></i>
                        </div>
                    </div>
                </div>
            </div>

        </div>

        <div class="row g-4">

            <!-- CHART -->
            <div class="col-lg-8">
                <div class="card shadow-sm border-0">
                    <div class="card-header">
                        <h6 class="mb-0">Grafik Transaksi 7 Hari Terakhir</h6>
                    </div>
                    <div class="card-body">
                        <canvas id="chartTransaksi" height="120"></canvas>
                    </div>
                </div>
            </div>

            <!-- QUICK ACTION -->
            <div class="col-lg-4">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-body d-flex flex-column justify-content-between">

                        <div>
                            <h6 class="mb-1">Mulai Transaksi</h6>
                            <small class="text-muted">
                                Input kendaraan masuk & keluar parkir
                            </small>
                        </div>

                        <div class="mt-3">
                            <a href="{{ route('petugas.transaksi') }}" class="btn btn-primary w-100">
                                <i class="ri-arrow-right-line me-1"></i> Buka Transaksi
                            </a>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>

    <!-- CHART JS -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('chartTransaksi');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: @json($labels),
                datasets: [{
                    label: 'Transaksi',
                    data: @json($data),
                    tension: 0.4,
                    fill: true
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });
    </script>
@endsection
