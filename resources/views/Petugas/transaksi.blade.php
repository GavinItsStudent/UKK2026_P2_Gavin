@extends('Layout.home')

@section('content')
    <div class="container-xxl container-p-y">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-0">Transaksi Parkir</h4>
                <small class="text-muted">Kelola kendaraan masuk & keluar</small>
            </div>

            <button onclick="printTable()" class="btn btn-outline-secondary">
                <i class="ri-printer-line me-1"></i> Print
            </button>
        </div>

        <div class="card p-3 mb-4">
            <form action="{{ route('petugas.transaksi.store') }}" method="POST" class="row g-3 align-items-end">
                @csrf

                <div class="col-md-3">
                    <label>Plat Nomor</label>
                    <input type="text" name="plat_nomor" class="form-control text-uppercase"
                        placeholder="Contoh: A 1234 BC" required>
                </div>

                <div class="col-md-2">
                    <label>Jenis</label>
                    <select name="jenis_kendaraan" class="form-select" required>
                        <option value="">-- Pilih --</option>
                        <option value="motor">Motor</option>
                        <option value="mobil">Mobil</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label>Warna</label>
                    <input type="text" name="warna" class="form-control" placeholder="Warna kendaraan" required>
                </div>

                <div class="col-md-3">
                    <label>Area</label>
                    <select name="area_id" class="form-select" required>
                        <option value="">-- Pilih Area --</option>
                        @foreach ($areas as $area)
                            @php
                                $sisa = $area->kapasitas - $area->terisi;
                            @endphp
                            <option value="{{ $area->id }}">
                                {{ $area->nama_area }} (Sisa: {{ $sisa }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 d-grid">
                    <button class="btn btn-primary">
                        Masuk
                    </button>
                </div>

            </form>
        </div>

        <div class="card mb-3">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">

                <form method="GET" class="d-flex align-items-center gap-2">

                    <div class="input-group" style="width: 280px;">
                        <span class="input-group-text bg-white">
                            <i class="ri-search-line"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Cari plat / warna / jenis...">
                    </div>

                    <button class="btn btn-primary">Cari</button>

                    @if (request('search'))
                        <a href="{{ route('petugas.transaksi') }}" class="btn btn-outline-secondary">
                            Reset
                        </a>
                    @endif

                </form>

                <small class="text-muted">
                    Total: {{ $transaksis->total() }} data
                </small>
            </div>
        </div>

        <div class="card" id="print-area">
            <div class="table-responsive">
                <table class="table table-sm align-middle">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Plat</th>
                            <th>Jenis</th>
                            <th>Warna</th>
                            <th>Area</th>
                            <th>Masuk</th>
                            <th>Status</th>
                            <th>Biaya</th>
                            <th class="text-end no-print">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($transaksis as $t)
                            <tr>

                                <td>{{ $loop->iteration }}</td>

                                <td class="fw-semibold text-uppercase">
                                    {{ $t->kendaraan->plat_nomor ?? '-' }}
                                </td>

                                <td>{{ ucfirst($t->kendaraan->jenis_kendaraan ?? '-') }}</td>

                                <td>{{ $t->kendaraan->warna ?? '-' }}</td>

                                <td>
                                    <span class="badge bg-dark">
                                        {{ $t->area->nama_area ?? '-' }}
                                    </span>
                                </td>

                                <td>
                                    {{ \Carbon\Carbon::parse($t->waktu_masuk)->format('d M Y H:i') }}
                                </td>

                                <td>
                                    @if ($t->status == 'masuk')
                                        <span class="badge bg-success">Masuk</span>
                                    @elseif ($t->status == 'menunggu_pembayaran')
                                        <span class="badge bg-warning text-dark">Menunggu</span>
                                    @else
                                        <span class="badge bg-secondary">Keluar</span>
                                    @endif
                                </td>

                                <td>
                                    @if ($t->biaya_bayar)
                                        <span class="fw-semibold text-success">
                                            Rp {{ number_format($t->biaya_bayar, 0, ',', '.') }}
                                        </span>
                                    @else
                                        <span class="text-muted">-</span>
                                    @endif
                                </td>

                                <td class="text-end no-print">
                                    <div class="d-flex justify-content-end gap-1">
                                        @if ($t->status == 'masuk')
                                            <a href="{{ route('petugas.transaksi.struk', $t->id) }}"
                                                class="btn btn-sm btn-info d-flex align-items-center justify-content-center"
                                                title="Cetak Struk Masuk">
                                                <i class="ri-ticket-line"></i>
                                            </a>


                                            <form action="{{ route('petugas.transaksi.keluar', $t->id) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-warning">
                                                    Hitung
                                                </button>
                                            </form>
                                        @elseif ($t->status == 'menunggu_pembayaran')
                                            <!-- CASH -->
                                            <form action="{{ route('petugas.transaksi.cash', $t->id) }}" method="POST">
                                                @csrf
                                                <button class="btn btn-sm btn-success">
                                                    Cash
                                                </button>
                                            </form>

                                            <!-- QRIS -->
                                            <a href="{{ route('petugas.transaksi.qris', $t->id) }}"
                                                class="btn btn-sm btn-primary">
                                                QRIS
                                            </a>

                                            <!-- ================= SELESAI ================= -->
                                        @else
                                            <!-- STRUK -->
                                            <a href="{{ route('petugas.transaksi.struk', $t->id) }}"
                                                class="btn btn-sm btn-info">
                                                Struk
                                            </a>
                                        @endif

                                        <!-- DELETE -->
                                        <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $t->id }}">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>

                                    </div>

                                    <form id="delete-{{ $t->id }}"
                                        action="{{ route('petugas.transaksi.destroy', $t->id) }}" method="POST"
                                        style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>

                                </td>
                            </tr>

                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    Belum ada transaksi
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- PAGINATION -->
            <div class="p-3">
                {{ $transaksis->links() }}
            </div>
        </div>

    </div>

    <!-- DELETE -->
    <script>
        document.querySelectorAll('.btn-delete').forEach(btn => {
            btn.addEventListener('click', function() {
                let id = this.dataset.id;

                if (confirm('Yakin hapus?')) {
                    document.getElementById('delete-' + id).submit();
                }
            });
        });
    </script>

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

    <style>
        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
@endsection
