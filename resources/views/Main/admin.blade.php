@extends('Layout.home')

@section('content')
    <div class="container-xxl container-p-y">

        <!-- HEADER -->
        <div class="mb-4">
            <h3 class="fw-bold mb-1">Dashboard Admin</h3>
            <small class="text-muted">Monitoring sistem parkir secara real-time</small>
        </div>

        <!-- STATISTICS -->
        <div class="row g-4 mb-4">

            <!-- CARD -->
            @php
                $cards = [
                    ['title' => 'Parkir Aktif', 'value' => $parkirAktif, 'icon' => 'ri-car-fill', 'color' => 'primary'],
                    [
                        'title' => 'Transaksi Hari Ini',
                        'value' => $totalTransaksi,
                        'icon' => 'ri-file-list-3-fill',
                        'color' => 'success',
                    ],
                    [
                        'title' => 'Pendapatan',
                        'value' => 'Rp ' . number_format($pendapatanHariIni, 0, ',', '.'),
                        'icon' => 'ri-money-dollar-circle-fill',
                        'color' => 'warning',
                    ],
                    [
                        'title' => 'Kapasitas',
                        'value' => "$totalTerisi / $totalKapasitas",
                        'icon' => 'ri-parking-box-fill',
                        'color' => 'info',
                    ],
                ];
            @endphp

            @foreach ($cards as $c)
                <div class="col-xl-3 col-md-6">
                    <div class="card border-0 shadow-sm h-100 hover-shadow transition">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <div>
                                <small class="text-muted">{{ $c['title'] }}</small>
                                <h3 class="fw-bold mb-0 mt-1">{{ $c['value'] }}</h3>
                            </div>
                            <div class="icon-box bg-{{ $c['color'] }} bg-opacity-10 text-{{ $c['color'] }}">
                                <i class="{{ $c['icon'] }}"></i>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach

        </div>

        <div class="row g-4">

            <!-- CHART -->
            <div class="col-lg-7">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header d-flex justify-content-between">
                        <h5 class="mb-0">Pendapatan 7 Hari</h5>
                    </div>
                    <div class="card-body">
                        <canvas id="chartPendapatan" height="120"></canvas>
                    </div>
                </div>
            </div>

            <!-- AREA -->
            <div class="col-lg-5">
                <div class="card shadow-sm border-0 h-100">
                    <div class="card-header">
                        <h5 class="mb-0">Kapasitas Area</h5>
                    </div>
                    <div class="card-body">

                        @foreach ($areas as $area)
                            @php
                                $persen = $area->kapasitas > 0 ? ($area->terisi / $area->kapasitas) * 100 : 0;
                            @endphp

                            <div class="mb-3">
                                <div class="d-flex justify-content-between">
                                    <span class="fw-semibold">{{ $area->nama_area }}</span>
                                    <small>{{ $area->terisi }}/{{ $area->kapasitas }}</small>
                                </div>

                                <div class="progress mt-1" style="height: 6px;">
                                    <div class="progress-bar 
                                {{ $persen > 80 ? 'bg-danger' : ($persen > 50 ? 'bg-warning' : 'bg-primary') }}"
                                        style="width: {{ $persen }}%">
                                    </div>
                                </div>
                            </div>
                        @endforeach

                    </div>
                </div>
            </div>

            <!-- LOG -->
            <div class="col-12">
                <div class="card shadow-sm border-0">
                    <div class="card-header">
                        <h5 class="mb-0">Aktivitas Terbaru</h5>
                    </div>
                    <div class="card-body">

                        @forelse($logs as $log)
                            <div class="d-flex mb-3">
                                <div class="me-3">
                                    <span class="badge bg-primary rounded-circle p-2">
                                        <i class="ri-user-line"></i>
                                    </span>
                                </div>

                                <div>
                                    <div class="fw-semibold">
                                        {{ $log->user->username ?? 'System' }}
                                    </div>
                                    <small class="text-muted">{{ $log->aktivitas }}</small><br>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($log->waktu_aktivitas)->diffForHumans() }}
                                    </small>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center">Belum ada aktivitas</p>
                        @endforelse

                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- STYLE -->
    <style>
        .icon-box {
            width: 50px;
            height: 50px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            font-size: 22px;
        }

        .hover-shadow:hover {
            transform: translateY(-3px);
            transition: .2s;
        }
    </style>

    <!-- CHART -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('chartPendapatan');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: {!! json_encode($chart->pluck('tanggal')) !!},
                datasets: [{
                    label: 'Pendapatan',
                    data: {!! json_encode($chart->pluck('total')) !!},
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 3
                }]
            },
            options: {
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString();
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection
