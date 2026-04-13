@extends('Layout.home')

@section('content')
    <div class="container-xxl container-p-y">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-0">Kelola Shift</h4>
                <small class="text-muted">Manajemen jam kerja petugas</small>
            </div>

            <button onclick="printShift()" class="btn btn-outline-secondary no-print">
                <i class="ri-printer-line me-1"></i> Print
            </button>
        </div>

        @if (session('success'))
            <div class="alert alert-success no-print">{{ session('success') }}</div>
        @endif

        {{-- FORM TAMBAH --}}
        <div class="card p-3 mb-4 no-print">
            <form action="{{ route('admin.shifts.store') }}" method="POST" class="row g-3 align-items-end">
                @csrf

                <div class="col-md-4">
                    <input type="text" name="nama_shift" class="form-control" placeholder="Contoh: Shift Pagi" required>
                </div>

                <div class="col-md-3">
                    <input type="time" name="jam_masuk" class="form-control" required>
                </div>

                <div class="col-md-3">
                    <input type="time" name="jam_keluar" class="form-control" required>
                </div>

                <div class="col-md-2 d-grid">
                    <button class="btn btn-primary">
                        <i class="ri-add-line"></i> Tambah
                    </button>
                </div>
            </form>
        </div>

        {{-- TABLE --}}
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
                                <td><b>{{ $shift->nama_shift }}</b></td>
                                <td>{{ \Carbon\Carbon::parse($shift->jam_masuk)->format('H:i') }}</td>
                                <td>{{ \Carbon\Carbon::parse($shift->jam_keluar)->format('H:i') }}</td>

                                <td class="text-end no-print">
                                    <button type="button" class="btn btn-sm btn-warning btn-edit"
                                        data-id="{{ $shift->id }}" data-nama="{{ $shift->nama_shift }}"
                                        data-masuk="{{ $shift->jam_masuk }}" data-keluar="{{ $shift->jam_keluar }}"
                                        data-bs-toggle="modal" data-bs-target="#editModal">
                                        <i class="ri-pencil-line"></i>
                                    </button>

                                    <form action="{{ route('admin.shifts.destroy', $shift->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Yakin hapus shift?')">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
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

    {{-- MODAL EDIT --}}
    <div class="modal fade" id="editModal">
        <div class="modal-dialog">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Edit Shift</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="text" name="nama_shift" id="editNama" class="form-control mb-3"
                            placeholder="Nama shift" required>

                        <input type="time" name="jam_masuk" id="editMasuk" class="form-control mb-3" required>

                        <input type="time" name="jam_keluar" id="editKeluar" class="form-control" required>
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- PRINT AREA --}}
    <div id="printOnly"></div>

    <script>
        function printShift() {
            let rows = document.querySelectorAll('#printArea tbody tr');

            let content = `
        <h3 style="text-align:center;margin-bottom:20px;">Laporan Data Shift</h3>
        <table border="1" cellspacing="0" cellpadding="8" width="100%">
        <tr>
            <th>Nama Shift</th>
            <th>Jam Masuk</th>
            <th>Jam Keluar</th>
        </tr>`;

            rows.forEach(row => {
                let cols = row.querySelectorAll('td');
                if (cols.length >= 3) {
                    content += `<tr>
                <td>${cols[0].innerText}</td>
                <td>${cols[1].innerText}</td>
                <td>${cols[2].innerText}</td>
            </tr>`;
                }
            });

            content += `</table>`;

            let printDiv = document.getElementById('printOnly');
            printDiv.innerHTML = content;
            window.print();
            printDiv.innerHTML = '';
        }

        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-edit').forEach(btn => {
                btn.addEventListener('click', function() {
                    let id = this.dataset.id;

                    document.getElementById('editForm').action =
                        "{{ route('admin.shifts.update', ':id') }}".replace(':id', id);
                    editNama.value = this.dataset.nama;
                    editMasuk.value = this.dataset.masuk;
                    editKeluar.value = this.dataset.keluar;
                });
            });
        });
    </script>

    <style>
        @media print {
            body * {
                visibility: hidden;
            }

            #printOnly,
            #printOnly * {
                visibility: visible;
            }

            #printOnly {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
@endsection
