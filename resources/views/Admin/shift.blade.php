@extends('Layout.home')

@section('content')
    <div class="container-xxl container-p-y">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-0">Kelola Shift</h4>
                <small class="text-muted">Manajemen jam kerja petugas</small>
            </div>

            <button onclick="printTable()" class="btn btn-outline-secondary">
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

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <!-- FORM TAMBAH -->
        <div class="card p-3 mb-4">
            <form action="{{ route('admin.shift.store') }}" method="POST" class="row g-3 align-items-end">
                @csrf

                <div class="col-md-4">
                    <label class="form-label">Nama Shift</label>
                    <input type="text" name="nama_shift" value="{{ old('nama_shift') }}" class="form-control"
                        placeholder="Contoh: Shift Pagi">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Jam Masuk</label>
                    <input type="time" name="jam_masuk" value="{{ old('jam_masuk') }}" class="form-control">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Jam Keluar</label>
                    <input type="time" name="jam_keluar" value="{{ old('jam_keluar') }}" class="form-control">
                </div>

                <div class="col-md-2 d-grid">
                    <button class="btn btn-primary">
                        <i class="ri-add-line"></i> Tambah
                    </button>
                </div>

            </form>
        </div>

        <!-- TABLE -->
        <div class="card" id="printArea">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Nama Shift</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                            <th class="text-end no-print">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($shifts as $shift)
                            <tr>
                                <td>{{ $shift->nama_shift }}</td>
                                <td>{{ \Carbon\Carbon::parse($shift->jam_masuk)->format('H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($shift->jam_keluar)->format('H:i') }}</td>

                                <td class="text-end no-print">

                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editShift{{ $shift->id }}">
                                        <i class="ri-pencil-line"></i>
                                    </button>

                                    <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $shift->id }}">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>

                                    <form id="delete-shift-{{ $shift->id }}"
                                        action="{{ route('admin.shift.destroy', $shift->id) }}" method="POST"
                                        style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>

                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted">
                                    Belum ada data shift
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- MODAL EDIT -->
    @foreach ($shifts as $shift)
        <div class="modal fade" id="editShift{{ $shift->id }}">
            <div class="modal-dialog">
                <form action="{{ route('admin.shift.update', $shift->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">Edit Shift</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            <div class="mb-3">
                                <label>Nama Shift</label>
                                <input type="text" name="nama_shift" class="form-control"
                                    value="{{ $shift->nama_shift }}">
                            </div>

                            <div class="mb-3">
                                <label>Jam Masuk</label>
                                <input type="time" name="jam_masuk" class="form-control"
                                    value="{{ $shift->jam_masuk }}">
                            </div>

                            <div class="mb-3">
                                <label>Jam Keluar</label>
                                <input type="time" name="jam_keluar" class="form-control"
                                    value="{{ $shift->jam_keluar }}">
                            </div>

                        </div>

                        <div class="modal-footer">
                            <button class="btn btn-primary">Update</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    @endforeach

    <!-- DELETE SCRIPT (FIXED) -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', function() {
                    let id = this.getAttribute('data-id');

                    Swal.fire({
                        title: 'Yakin hapus?',
                        text: "Data shift akan dihapus!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('delete-shift-' + id).submit();
                        }
                    });
                });
            });
        });
    </script>

    <!-- PRINT -->
    <script>
        function printTable() {
            let content = document.getElementById('printArea').innerHTML;
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
