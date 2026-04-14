@extends('Layout.home')

@section('content')
    <div class="container-xxl container-p-y">

        <!-- HEADER -->
        <div class="mb-4">
            <h4 class="fw-bold mb-1">Owner Dashboard</h4>
            <small class="text-muted">Ringkasan performa parkir hari ini</small>
        </div>

        <!-- STATS CARDS -->
        <div class="row g-3">

            <!-- TOTAL PENDAPATAN -->
            <div class="col-md-3">
                <div class="card p-3 shadow-sm">
                    <small class="text-muted">Pendapatan Hari Ini</small>
                    <h5 class="fw-bold mt-2">
                        Rp {{ number_format($pendapatanHariIni ?? 0, 0, ',', '.') }}
                    </h5>
                </div>
            </div>

            <!-- TRANSAKSI HARI INI -->
            <div class="col-md-3">
                <div class="card p-3 shadow-sm">
                    <small class="text-muted">Transaksi Hari Ini</small>
                    <h5 class="fw-bold mt-2">
                        {{ $totalTransaksi ?? 0 }}
                    </h5>
                </div>
            </div>

            <!-- KENDARAAN AKTIF -->
            <div class="col-md-3">
                <div class="card p-3 shadow-sm">
                    <small class="text-muted">Kendaraan Masih Parkir</small>
                    <h5 class="fw-bold mt-2">
                        {{ $parkirAktif ?? 0 }}
                    </h5>
                </div>
            </div>

            <!-- TOTAL AREA TERISI -->
            <div class="col-md-3">
                <div class="card p-3 shadow-sm">
                    <small class="text-muted">Kapasitas Terpakai</small>
                    <h5 class="fw-bold mt-2">
                        {{ $totalTerisi ?? 0 }} / {{ $totalKapasitas ?? 0 }}
                    </h5>
                </div>
            </div>

        </div>

        <!-- SECOND ROW -->
        <div class="row mt-4 g-3">

            <!-- AREA PARKIR -->
            <div class="col-md-6">
                <div class="card p-3 shadow-sm">
                    <h6 class="fw-bold mb-3">Status Area Parkir</h6>

                    <div class="table-responsive">
                        <table class="table table-sm align-middle">
                            <thead>
                                <tr>
                                    <th>Area</th>
                                    <th>Kapasitas</th>
                                    <th>Terisi</th>
                                    <th>Sisa</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($areas as $a)
                                    <tr>
                                        <td>{{ $a->nama_area }}</td>
                                        <td>{{ $a->kapasitas }}</td>
                                        <td>{{ $a->terisi }}</td>
                                        <td>
                                            <span
                                                class="badge bg-{{ $a->kapasitas - $a->terisi > 0 ? 'success' : 'danger' }}">
                                                {{ $a->kapasitas - $a->terisi }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>

                        </table>
                    </div>
                </div>
            </div>

            <!-- LOG AKTIVITAS -->
            <div class="col-md-6">
                <div class="card p-3 shadow-sm">
                    <h6 class="fw-bold mb-3">Aktivitas Terbaru</h6>

                    @foreach ($logs as $log)
                        <div class="d-flex justify-content-between border-bottom py-2">
                            <div>
                                <small class="fw-bold">
                                    {{ $log->user->username ?? '-' }}
                                </small>
                                <br>
                                <small class="text-muted">
                                    {{ $log->aktivitas }}
                                </small>
                            </div>

                            <small class="text-muted">
                                {{ \Carbon\Carbon::parse($log->waktu_aktivitas)->diffForHumans() }}
                            </small>
                        </div>
                    @endforeach

                </div>
            </div>

        </div>

    </div>
@endsection
