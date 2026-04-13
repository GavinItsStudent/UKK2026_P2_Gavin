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
                <h4 class="fw-bold mb-0">Kelola Area Parkir</h4>
                <small class="text-muted">Manajemen area parkir</small>
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
            <form action="{{ route('admin.areas.store') }}" method="POST" class="row g-3 align-items-end">
                @csrf

                <div class="col-md-4">
                    <label class="form-label">Nama Area</label>
                    <input type="text" name="nama_area" value="{{ old('nama_area') }}" class="form-control"
                        placeholder="Contoh: Area A">
                </div>

                <div class="col-md-4">
                    <label class="form-label">Kapasitas</label>
                    <input type="number" name="kapasitas" value="{{ old('kapasitas') }}" min="1"
                        class="form-control" placeholder="Contoh: 50">
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
                            <th>Nama Area</th>
                            <th>Kapasitas</th>
                            <th>Terisi</th>
                            <th>Status</th>
                            <th class="text-end no-print">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse($areas as $area)
                            @php
                                $persen = $area->kapasitas > 0 ? ($area->terisi / $area->kapasitas) * 100 : 0;
                            @endphp
                            <tr>
                                <td><strong>{{ $area->nama_area }}</strong></td>

                                <td>{{ $area->kapasitas }}</td>

                                <td>
                                    <span class="badge bg-primary">
                                        {{ $area->terisi }}
                                    </span>
                                </td>

                                <!-- STATUS BAR (PRO LOOK) -->
                                <td style="min-width:150px;">
                                    <div class="progress" style="height:6px;">
                                        <div class="progress-bar 
                                    {{ $persen > 80 ? 'bg-danger' : ($persen > 50 ? 'bg-warning' : 'bg-success') }}"
                                            style="width: {{ $persen }}%">
                                        </div>
                                    </div>
                                    <small class="text-muted">{{ round($persen) }}%</small>
                                </td>

                                <td class="text-end no-print">

                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editArea{{ $area->id }}">
                                        <i class="ri-pencil-line"></i>
                                    </button>

                                    <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $area->id }}">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>

                                    <form id="delete-area-{{ $area->id }}"
                                        action="{{ route('admin.areas.destroy', $area->id) }}" method="POST"
                                        style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>

                                </td>
                            </tr>

                            <!-- MODAL EDIT -->
                            <div class="modal fade" id="editArea{{ $area->id }}">
                                <div class="modal-dialog">
                                    <form action="{{ route('admin.areas.update', $area->id) }}" method="POST">
                                        @csrf
                                        @method('PUT')

                                        <div class="modal-content">

                                            <div class="modal-header">
                                                <h5 class="modal-title">Edit Area</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <div class="modal-body">

                                                <div class="mb-3">
                                                    <label>Nama Area</label>
                                                    <input type="text" name="nama_area" class="form-control"
                                                        value="{{ $area->nama_area }}">
                                                </div>

                                                <div class="mb-3">
                                                    <label>Kapasitas</label>
                                                    <input type="number" name="kapasitas" min="1"
                                                        class="form-control" value="{{ $area->kapasitas }}">
                                                </div>

                                                <div class="mb-3">
                                                    <label>Terisi (Auto)</label>
                                                    <input type="number" class="form-control bg-light text-muted"
                                                        value="{{ $area->terisi }}" readonly>
                                                </div>

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
                                <td colspan="5" class="text-center text-muted">
                                    Belum ada data area parkir
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
                        text: "Area akan dihapus!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya, hapus!'
                    }).then((r) => {
                        if (r.isConfirmed) {
                            document.getElementById('delete-area-' + id).submit();
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

@endsection
