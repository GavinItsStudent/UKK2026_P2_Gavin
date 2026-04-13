@extends('Layout.home')

@section('content')
    <style>
        input[readonly] {
            background-color: #f1f1f1;
            cursor: not-allowed;
        }

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
                <h4 class="fw-bold mb-0">Kelola Tarif Parkir</h4>
                <small class="text-muted">Manajemen tarif kendaraan</small>
            </div>

            <button onclick="printTable()" class="btn btn-outline-secondary">
                <i class="ri-printer-line me-1"></i> Print
            </button>
        </div>

        <!-- ALERT -->
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
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
            <form action="{{ route('admin.tarif.store') }}" method="POST" class="row g-3 align-items-end">
                @csrf

                <div class="col-md-4">
                    <label class="form-label">Jenis Kendaraan</label>
                    <select name="jenis_kendaraan" class="form-select">
                        <option value="">-- Pilih --</option>
                        <option value="motor" {{ old('jenis_kendaraan') == 'motor' ? 'selected' : '' }}>Motor</option>
                        <option value="mobil" {{ old('jenis_kendaraan') == 'mobil' ? 'selected' : '' }}>Mobil</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <label class="form-label">Harga / Jam</label>
                    <input type="text" name="tarif_per_jam" value="{{ old('tarif_per_jam') }}"
                        class="form-control rupiah" placeholder="Contoh: Rp 2.000">
                </div>

                <div class="col-md-4 d-grid">
                    <label class="form-label invisible">Button</label>
                    <button class="btn btn-primary">
                        <i class="ri-add-line me-1"></i> Tambah
                    </button>
                </div>

            </form>
        </div>

        <!-- TABLE -->
        <div class="card" id="print-area">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Jenis Kendaraan</th>
                            <th>Harga / Jam</th>
                            <th class="text-end no-print">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($tarif as $t)
                            <tr>
                                <td class="text-capitalize">{{ $t->jenis_kendaraan }}</td>
                                <td><strong>Rp {{ number_format($t->tarif_per_jam, 0, ',', '.') }}</strong></td>

                                <td class="text-end no-print">

                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editTarif{{ $t->id }}">
                                        <i class="ri-pencil-line"></i>
                                    </button>

                                    <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $t->id }}">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>

                                    <form id="delete-tarif-{{ $t->id }}"
                                        action="{{ route('admin.tarif.destroy', $t->id) }}" method="POST"
                                        style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>

                                </td>
                            </tr>

                            <!-- MODAL EDIT -->
                            <div class="modal fade" id="editTarif{{ $t->id }}">
                                <div class="modal-dialog">
                                    <form action="{{ route('admin.tarif.update', $t->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Tarif</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">

                                                <div class="mb-3">
                                                    <label>Jenis Kendaraan</label>
                                                    <input type="text" class="form-control"
                                                        value="{{ ucfirst($t->jenis_kendaraan) }}" readonly>
                                                    <input type="hidden" name="jenis_kendaraan"
                                                        value="{{ $t->jenis_kendaraan }}">
                                                </div>

                                                <div class="mb-3">
                                                    <label>Harga / Jam</label>
                                                    <input type="text" class="form-control rupiah"
                                                        value="Rp {{ number_format($t->tarif_per_jam, 0, ',', '.') }}">
                                                </div>

                                                <!-- VALUE ASLI -->
                                                <input type="hidden" name="tarif_per_jam"
                                                    id="realTarif{{ $t->id }}" value="{{ $t->tarif_per_jam }}">

                                            </div>

                                            <div class="modal-footer">
                                                <button class="btn btn-primary">Update</button>
                                            </div>

                                        </div>
                                    </form>
                                </div>
                            </div>

                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted">
                                    Belum ada data tarif
                                </td>
                            </tr>
                        @endforelse
                    </tbody>

                </table>
            </div>
        </div>

    </div>

    <!-- DELETE -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', function() {
                    let id = this.dataset.id;

                    Swal.fire({
                        title: 'Yakin hapus?',
                        text: "Data tarif akan dihapus!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus!'
                    }).then((r) => {
                        if (r.isConfirmed) {
                            document.getElementById('delete-tarif-' + id).submit();
                        }
                    });
                });
            });
        });
    </script>

    <!-- FORMAT RUPIAH + FIX VALUE -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {

            document.querySelectorAll('.rupiah').forEach(input => {

                input.addEventListener('input', function() {

                    let angka = this.value.replace(/[^0-9]/g, '');
                    let format = new Intl.NumberFormat('id-ID').format(angka);

                    this.value = angka ? 'Rp ' + format : '';

                    // cari hidden input terdekat
                    let hidden = this.closest('form')?.querySelector(
                        'input[type=hidden][name=tarif_per_jam]');
                    if (hidden) hidden.value = angka;
                });

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
@endsection
