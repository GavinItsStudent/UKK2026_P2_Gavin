@extends('Layout.home')

@section('content')
    <div class="container-xxl container-p-y d-flex justify-content-center">

        <div class="card shadow p-4 text-center" style="width: 350px;">

            <h5 class="fw-bold mb-3">Pembayaran QRIS</h5>

            <p>Scan QR untuk bayar:</p>

            <!-- QR DUMMY -->
            <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=PAYMENT-{{ $trx->id }}"
                class="mb-3">

            <h5 class="fw-bold">
                Rp {{ number_format($trx->biaya_bayar, 0, ',', '.') }}
            </h5>

            <div class="mt-3 d-grid gap-2">

                <!-- SIMULASI SUKSES -->
                <a href="{{ route('petugas.transaksi.qris.success', $trx->id) }}" class="btn btn-success">
                    ✔ Sudah Bayar
                </a>

                <a href="{{ route('petugas.transaksi') }}" class="btn btn-secondary">
                    Kembali
                </a>

            </div>

        </div>

    </div>
@endsection
