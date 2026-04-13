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
                <h4 class="fw-bold mb-0">Log Aktivitas</h4>
                <small class="text-muted">Riwayat aktivitas pengguna sistem</small>
            </div>

            <button onclick="printTable()" class="btn btn-outline-secondary no-print">
                <i class="ri-printer-line me-1"></i> Print
            </button>
        </div>

        <!-- ALERT -->
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- TABLE -->
        <div class="card" id="print-area">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Aktivitas</th>
                            <th>Waktu</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($logs as $log)
                            <tr>

                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($log->user->username ?? 'User') }}"
                                            class="rounded-circle me-2" width="35">

                                        <div>
                                            <b>{{ $log->user->username ?? '-' }}</b>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="text-muted">
                                        {{ $log->aktivitas }}
                                    </span>
                                </td>

                                <td>
                                    <small class="text-muted">
                                        {{ \Carbon\Carbon::parse($log->waktu_aktivitas)->format('d M Y H:i') }}
                                    </small>
                                    <br>
                                    <small class="text-secondary">
                                        {{ \Carbon\Carbon::parse($log->waktu_aktivitas)->diffForHumans() }}
                                    </small>
                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    Belum ada aktivitas
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
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
