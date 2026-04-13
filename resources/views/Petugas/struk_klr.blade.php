@extends('Layout.home')

@section('content')
    <div class="container-xxl container-p-y d-flex justify-content-center">

        <div class="card shadow p-4 text-center struk-box">

            <h5 class="fw-bold mb-2">STRUK KELUAR</h5>

            <hr class="garis">

            <h4 class="fw-bold">
                {{ $trx->kendaraan->plat_nomor }}
            </h4>

            <small class="text-muted">
                {{ $trx->kendaraan->jenis_kendaraan }} | {{ $trx->kendaraan->warna }}
            </small>

            <p class="mt-2">
                Masuk: {{ \Carbon\Carbon::parse($trx->waktu_masuk)->format('d-m-Y H:i') }} <br>
                Keluar: {{ \Carbon\Carbon::parse($trx->waktu_keluar)->format('d-m-Y H:i') }}
            </p>

            <hr class="garis">

            @php
                $menit = \Carbon\Carbon::parse($trx->waktu_masuk)->diffInMinutes($trx->waktu_keluar);
                $jam = ceil($menit / 60);
                if ($jam < 1) {
                    $jam = 1;
                }
            @endphp

            <p>Durasi: {{ $jam }} Jam</p>

            <h5 class="fw-bold">
                Total: Rp {{ number_format($trx->biaya_bayar, 0, ',', '.') }}
            </h5>

            <div class="no-print d-grid gap-2 mt-3">
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
