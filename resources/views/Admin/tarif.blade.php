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

    <!-- FORM TAMBAH -->
    <div class="card p-3 mb-4">
        <form action="{{ route('admin.tarif.store') }}" method="POST" class="row g-3 align-items-end">
            @csrf

            <div class="col-md-4">
                <label class="form-label">Jenis Kendaraan</label>
                <select name="jenis_kendaraan" class="form-select">
                    <option value="">-- Pilih --</option>
                    <option value="motor">Motor</option>
                    <option value="mobil">Mobil</option>
                </select>
            </div>

            <div class="col-md-4">
                <label class="form-label">Harga / Jam</label>
                <input type="text" name="tarif_per_jam" class="form-control rupiah"
                    placeholder="Contoh: Rp 2.000">
            </div>

            <!-- BUTTON RAPIH -->
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
            <table class="table table-sm">
                <thead>
                    <tr>
                        <th>Jenis Kendaraan</th>
                        <th>Harga / Jam</th>
                        <th class="text-end no-print">Aksi</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach ($tarif as $t)
                        <tr>
                            <td class="text-capitalize">{{ $t->jenis_kendaraan }}</td>
                            <td><strong>Rp {{ number_format($t->tarif_per_jam) }}</strong></td>

                            <td class="text-end no-print">

                                <!-- EDIT -->
                                <button class="btn btn-sm btn-warning"
                                    data-bs-toggle="modal"
                                    data-bs-target="#editTarif{{ $t->id }}">
                                    <i class="ri-pencil-line"></i>
                                </button>

                                <!-- DELETE -->
                                <button class="btn btn-sm btn-danger btn-delete"
                                    data-id="{{ $t->id }}">
                                    <i class="ri-delete-bin-line"></i>
                                </button>

                                <form id="delete-tarif-{{ $t->id }}"
                                    action="{{ route('admin.tarif.destroy', $t->id) }}"
                                    method="POST" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>

                            </td>
                        </tr>

                        <!-- MODAL EDIT -->
                        <div class="modal fade" id="editTarif{{ $t->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('admin.tarif.update', $t->id) }}" method="POST">
                                    @csrf
                                    @method('PUT')

                                    <div class="modal-content p-3">

                                        <div class="mb-3">
                                            <label>Jenis Kendaraan</label>
                                            <input type="text" class="form-control bg-light text-muted"
                                                value="{{ ucfirst($t->jenis_kendaraan) }}" readonly>

                                            <input type="hidden" name="jenis_kendaraan"
                                                value="{{ $t->jenis_kendaraan }}">
                                        </div>

                                        <div class="mb-3">
                                            <label>Harga / Jam</label>
                                            <input type="text" name="tarif_per_jam"
                                                class="form-control rupiah"
                                                value="{{ $t->tarif_per_jam }}">
                                        </div>

                                        <button class="btn btn-primary">Update</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    @endforeach
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

<!-- FORMAT RUPIAH -->
<script>
document.querySelectorAll('.rupiah').forEach(input => {

    input.addEventListener('input', function() {

        let value = this.value.replace(/[^,\d]/g, '').toString();
        let split = value.split(',');
        let sisa = split[0].length % 3;
        let rupiah = split[0].substr(0, sisa);
        let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }

        this.value = rupiah ? 'Rp ' + rupiah : '';
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