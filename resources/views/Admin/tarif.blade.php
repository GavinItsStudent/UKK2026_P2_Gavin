@extends('Layout.home')

@section('content')
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

    <div class="container-xxl container-p-y">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-0">Kelola Tarif Parkir</h4>
                <small class="text-muted">Manajemen tarif kendaraan</small>
            </div>

            <button onclick="printTarif()" class="btn btn-outline-secondary no-print">
                <i class="ri-printer-line me-1"></i> Print
            </button>
        </div>

        @if (session('success'))
            <div class="alert alert-success no-print">{{ session('success') }}</div>
        @endif

        {{-- FORM TAMBAH --}}
        <div class="card p-3 mb-4 no-print">
            <form action="{{ route('admin.tarifs.store') }}" method="POST" class="row g-3">
                @csrf

                <div class="col-md-4">
                    <select name="jenis_kendaraan" class="form-select" required>
                        <option value="">Pilih jenis kendaraan</option>
                        <option value="motor">Motor</option>
                        <option value="mobil">Mobil</option>
                    </select>
                </div>

                <div class="col-md-4">
                    <input type="text" id="rupiahAdd" class="form-control" placeholder="Contoh: Rp 2.000" required>
                    <input type="hidden" name="tarif_per_jam" id="realAdd">
                </div>

                <div class="col-md-4 d-grid">
                    <button class="btn btn-primary">
                        <i class="ri-add-line"></i> Tambah Tarif
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
                            <th>Jenis Kendaraan</th>
                            <th>Harga / Jam</th>
                            <th class="text-end no-print">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($tarif as $t)
                            <tr>
                                <td class="text-capitalize"><b>{{ $t->jenis_kendaraan }}</b></td>
                                <td><b>Rp {{ number_format($t->tarif_per_jam, 0, ',', '.') }}</b></td>
                                <td class="text-end no-print">
                                    <button class="btn btn-sm btn-warning btn-edit" data-id="{{ $t->id }}"
                                        data-jenis="{{ $t->jenis_kendaraan }}" data-tarif="{{ $t->tarif_per_jam }}"
                                        data-bs-toggle="modal" data-bs-target="#editModal">
                                        <i class="ri-pencil-line"></i>
                                    </button>

                                    <form action="{{ route('admin.tarifs.destroy', $t->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-sm btn-danger"
                                            onclick="return confirm('Yakin hapus tarif?')">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
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

    {{-- MODAL EDIT (SATU SAJA) --}}
    <div class="modal fade" id="editModal">
        <div class="modal-dialog">
            <form id="editForm" method="POST">
                @csrf
                @method('PUT')

                <div class="modal-content">
                    <div class="modal-header">
                        <h5>Edit Tarif</h5>
                        <button class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">
                        <input type="text" id="editJenis" class="form-control mb-3" readonly>

                        <input type="text" id="rupiahEdit" class="form-control" placeholder="Masukkan tarif baru"
                            required>

                        <input type="hidden" name="jenis_kendaraan" id="realJenis">
                        <input type="hidden" name="tarif_per_jam" id="realEdit">
                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-primary">Update</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div id="printOnly"></div>

    <script>
        function formatRupiah(input, hidden) {
            input.addEventListener('input', function() {
                let angka = this.value.replace(/[^0-9]/g, '');
                this.value = angka ? 'Rp ' + new Intl.NumberFormat('id-ID').format(angka) : '';
                hidden.value = angka;
            });
        }

        // ADD
        formatRupiah(
            document.getElementById('rupiahAdd'),
            document.getElementById('realAdd')
        );

        // EDIT
        formatRupiah(
            document.getElementById('rupiahEdit'),
            document.getElementById('realEdit')
        );

        document.querySelectorAll('.btn-edit').forEach(btn => {
            btn.addEventListener('click', function() {
                let id = this.dataset.id;

                editForm.action =
                    "{{ route('admin.tarifs.update', ':id') }}".replace(':id', id);
                editJenis.value = this.dataset.jenis;
                realJenis.value = this.dataset.jenis;

                let tarif = this.dataset.tarif;
                rupiahEdit.value = 'Rp ' + new Intl.NumberFormat('id-ID').format(tarif);
                realEdit.value = tarif;
            });
        });

        function printTarif() {
            let rows = document.querySelectorAll('#printArea tbody tr');

            let content = `
    <h3 style="text-align:center;margin-bottom:20px;">Laporan Data Tarif Parkir</h3>
    <table border="1" cellspacing="0" cellpadding="8" width="100%">
    <tr>
        <th>Jenis Kendaraan</th>
        <th>Harga / Jam</th>
    </tr>`;

            rows.forEach(row => {
                let cols = row.querySelectorAll('td');
                if (cols.length >= 2) {
                    content += `<tr>
                <td>${cols[0].innerText}</td>
                <td>${cols[1].innerText}</td>
            </tr>`;
                }
            });

            content += `</table>`;
            printOnly.innerHTML = content;
            window.print();
            printOnly.innerHTML = '';
        }
    </script>
@endsection
