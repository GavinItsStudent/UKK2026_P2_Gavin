@extends('Layout.home')

@section('content')
    <div class="container-xxl container-p-y d-flex justify-content-center">

        <div class="card shadow p-4 text-center struk-box">

            <h5 class="fw-bold mb-2">STRUK MASUK</h5>

            <hr class="garis">

            <h4 class="fw-bold">
                {{ $trx->kendaraan->plat_nomor }}
            </h4>

            <small class="text-muted">
                {{ $trx->kendaraan->jenis_kendaraan }} | {{ $trx->kendaraan->warna }}
            </small>

            <p class="mt-2">
                Area: {{ $trx->area->nama_area }}
            </p>

            <p>
                Masuk:
                {{ \Carbon\Carbon::parse($trx->waktu_masuk)->format('d-m-Y H:i') }}
            </p>

            <hr class="garis">

            <span class="badge bg-success mb-3">MASUK</span>

            <div class="no-print d-grid gap-2">
                <button onclick="window.print()" class="btn btn-primary">CETAK</button>
                <a href="{{ route('petugas.transaksi') }}" class="btn btn-outline-secondary">Kembali</a>
            </div>

        </div>
    </div>

    <style>
        .struk-box {
            width: 300px;
            font-family: monospace;
        }

        .garis {
            border-top: 1px dashed black;
        }

        @media print {
            body * {
                visibility: hidden;
            }

            .struk-box,
            .struk-box * {
                visibility: visible;
            }

            .struk-box {
                position: absolute;
                left: 50%;
                top: 10px;
                transform: translateX(-50%);
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
@endsection
