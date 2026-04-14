@extends('Layout.home')

@section('content')
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>

    <div class="container-xxl container-p-y">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-0">Rekap Laporan</h4>
                <small class="text-muted">Rekap data parkir & transaksi</small>
            </div>

            <button onclick="printTable()" class="btn btn-outline-secondary">
                <i class="ri-printer-line me-1"></i> Print
            </button>
        </div>

        <!-- FILTER -->
        <div class="card p-3 mb-4 no-print">
            <form method="GET" class="row g-3 align-items-end">

                <div class="col-md-3">
                    <label>Dari Tanggal</label>
                    <input type="date" name="dari" value="{{ request('dari') }}" class="form-control">
                </div>

                <div class="col-md-3">
                    <label>Sampai Tanggal</label>
                    <input type="date" name="sampai" value="{{ request('sampai') }}" class="form-control">
                </div>

                <div class="col-md-2 d-grid">
                    <button class="btn btn-primary">
                        <i class="ri-filter-line me-1"></i> Filter
                    </button>
                </div>

                <div class="col-md-2 d-grid">
                    <a href="{{ route('owner.rekap') }}" class="btn btn-outline-secondary">
                        Reset
                    </a>
                </div>

            </form>
        </div>

        <!-- TAB -->
        <div class="card" id="print-area">

            <ul class="nav nav-tabs px-3 pt-3">
                <li class="nav-item">
                    <button class="nav-link active" data-bs-toggle="tab" data-bs-target="#parkir">
                        Rekap Parkir
                    </button>
                </li>
                <li class="nav-item">
                    <button class="nav-link" data-bs-toggle="tab" data-bs-target="#transaksi">
                        Rekap Transaksi
                    </button>
                </li>
            </ul>

            <div class="tab-content p-3">

                <!-- ================= PARKIR ================= -->
                <div class="tab-pane fade show active" id="parkir">

                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Plat</th>
                                    <th>Jenis</th>
                                    <th>Area</th>
                                    <th>Waktu Masuk</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($parkir as $p)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration + ($parkir->currentPage() - 1) * $parkir->perPage() }}
                                        </td>
                                        <td class="text-uppercase">{{ $p->kendaraan->plat_nomor }}</td>
                                        <td>{{ ucfirst($p->kendaraan->jenis_kendaraan) }}</td>
                                        <td>{{ $p->area->nama_area ?? '-' }}</td>
                                        <td>{{ $p->waktu_masuk }}</td>
                                        <td>
                                            <span class="badge bg-{{ $p->status == 'masuk' ? 'warning' : 'success' }}">
                                                {{ $p->status }}
                                            </span>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINATION -->
                    <div class="mt-3">
                        {{ $parkir->withQueryString()->links() }}
                    </div>

                </div>

                <!-- ================= TRANSAKSI ================= -->
                <div class="tab-pane fade" id="transaksi">

                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Plat</th>
                                    <th>Durasi</th>
                                    <th>Biaya</th>
                                    <th>Metode</th>
                                    <th>Waktu Keluar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($transaksi as $t)
                                    <tr>
                                        <td>
                                            {{ $loop->iteration + ($transaksi->currentPage() - 1) * $transaksi->perPage() }}
                                        </td>
                                        <td class="text-uppercase">{{ $t->kendaraan->plat_nomor }}</td>
                                        <td>{{ $t->durasi_jam }} Jam</td>
                                        <td>Rp {{ number_format($t->biaya_bayar, 0, ',', '.') }}</td>
                                        <td>{{ strtoupper($t->metode_bayar) }}</td>
                                        <td>{{ $t->waktu_keluar }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="text-center text-muted">Tidak ada data</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- PAGINATION -->
                    <div class="mt-3">
                        {{ $transaksi->withQueryString()->links() }}
                    </div>

                </div>

            </div>
        </div>

    </div>

    <!-- PRINT -->
    <script>
        function printTable() {
            let content = document.getElementById('print-area').innerHTML;
            let original = document.body.innerHTML;

            document.body.innerHTML = content;
            window.print();
            document.body.innerHTML = original;
            location.reload();
        }
    </script>
@endsection
