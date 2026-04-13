@extends('Layout.home')

@section('content')
    <div class="container-xxl container-p-y">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-0">Kelola Kendaraan</h4>
                <small class="text-muted">Manajemen kendaraan sistem</small>
            </div>

            <button onclick="printTable()" class="btn btn-outline-secondary">
                <i class="ri-printer-line me-1"></i> Print
            </button>
        </div>

        <!-- FORM TAMBAH -->
        <div class="card p-3 mb-4">
            <form action="{{ route('admin.kendaraan.store') }}" method="POST" class="row g-3 align-items-end">
                @csrf

                <div class="col-md-3">
                    <label class="form-label">Plat Nomor</label>
                    <input type="text" name="plat_nomor" class="form-control" placeholder="Contoh: A 1234 BC">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Jenis</label>
                    <select name="jenis_kendaraan" class="form-select">
                        <option value="">-- Pilih --</option>
                        <option value="motor">Motor</option>
                        <option value="mobil">Mobil</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Warna</label>
                    <input type="text" name="warna" class="form-control" placeholder="Hitam">
                </div>

                <!-- 🔥 AREA + SLOT -->
                <div class="col-md-3">
                    <label class="form-label">Area Parkir</label>
                    <select name="area_id" class="form-select">
                        <option value="">-- Pilih Area --</option>
                        @foreach ($areas as $a)
                            <option value="{{ $a->id }}">
                                {{ $a->nama_area }} (Sisa: {{ $a->sisa }})
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-2 d-grid">
                    <label class="form-label invisible">Button</label>
                    <button class="btn btn-primary">
                        <i class="ri-add-line me-1"></i> Tambah
                    </button>
                </div>

            </form>
        </div>

        <!-- SEARCH -->
        <div class="card mb-3">
            <div class="card-body d-flex justify-content-between align-items-center flex-wrap gap-2">

                <!-- LEFT: SEARCH -->
                <form method="GET" class="d-flex align-items-center gap-2">

                    <div class="input-group" style="width: 280px;">
                        <span class="input-group-text bg-white">
                            <i class="ri-search-line"></i>
                        </span>
                        <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                            placeholder="Cari kendaraan...">
                    </div>

                    <button class="btn btn-primary">
                        Cari
                    </button>

                    @if (request('search'))
                        <a href="{{ route('admin.kendaraan') }}" class="btn btn-outline-secondary">
                            Reset
                        </a>
                    @endif

                </form>

                <!-- RIGHT: OPTIONAL INFO -->
                <small class="text-muted">
                    Total: {{ $kendaraans->total() }} data
                </small>

            </div>
        </div>

        <!-- TABLE -->
        <div class="card" id="print-area">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Plat</th>
                            <th>Jenis</th>
                            <th>Warna</th>
                            <th>Area</th>
                            <th class="text-end no-print">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($kendaraans as $k)
                            <tr>
                                <td>{{ $loop->iteration + ($kendaraans->currentPage() - 1) * $kendaraans->perPage() }}</td>
                                <td class="text-uppercase">{{ $k->plat_nomor }}</td>

                                <td>
                                    @if ($k->jenis_kendaraan == 'motor')
                                        <span class="badge bg-info">Motor</span>
                                    @else
                                        <span class="badge bg-primary">Mobil</span>
                                    @endif
                                </td>

                                <td>{{ $k->warna }}</td>
                                <td>{{ $k->area->nama_area ?? '-' }}</td>

                                <td class="text-end no-print">
                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editModal{{ $k->id }}">
                                        <i class="ri-pencil-line"></i>
                                    </button>

                                    <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $k->id }}">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>

                                    <form id="delete-kendaraan-{{ $k->id }}"
                                        action="{{ route('admin.kendaraan.destroy', $k->id) }}" method="POST"
                                        style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                @foreach ($kendaraans as $k)
                    <div class="modal fade" id="editModal{{ $k->id }}" tabindex="-1">
                        <div class="modal-dialog">
                            <form action="{{ route('admin.kendaraan.update', $k->id) }}" method="POST">
                                @csrf
                                @method('PUT')

                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit Kendaraan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">

                                        <div class="mb-3">
                                            <label>Plat Nomor</label>
                                            <input type="text" name="plat_nomor" class="form-control"
                                                value="{{ $k->plat_nomor }}">
                                        </div>

                                        <div class="mb-3">
                                            <label>Jenis</label>
                                            <select name="jenis_kendaraan" class="form-select">
                                                <option value="motor"
                                                    {{ $k->jenis_kendaraan == 'motor' ? 'selected' : '' }}>Motor</option>
                                                <option value="mobil"
                                                    {{ $k->jenis_kendaraan == 'mobil' ? 'selected' : '' }}>Mobil</option>
                                            </select>
                                        </div>

                                        <div class="mb-3">
                                            <label>Warna</label>
                                            <input type="text" name="warna" class="form-control"
                                                value="{{ $k->warna }}">
                                        </div>

                                        <div class="mb-3">
                                            <label>Area Parkir</label>
                                            <select name="area_id" class="form-select">
                                                @foreach ($areas as $a)
                                                    <option value="{{ $a->id }}"
                                                        {{ $k->area_id == $a->id ? 'selected' : '' }}>
                                                        {{ $a->nama_area }} (Sisa: {{ $a->sisa }})
                                                    </option>
                                                @endforeach
                                            </select>
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
            </div>

            <!-- PAGINATION -->
            <div class="p-3">
                {{ $kendaraans->withQueryString()->links() }}
            </div>
        </div>

    </div>

    <!-- DELETE SCRIPT -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', function() {
                    let id = this.dataset.id;

                    Swal.fire({
                        title: 'Yakin hapus?',
                        icon: 'warning',
                        showCancelButton: true
                    }).then((r) => {
                        if (r.isConfirmed) {
                            document.getElementById('delete-kendaraan-' + id).submit();
                        }
                    });
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

    <style>
        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
@endsection
