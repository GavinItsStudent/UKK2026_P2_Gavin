@extends('Layout.home')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-0">Kelola Shift</h4>
                <small class="text-muted">Manajemen jam kerja petugas</small>
            </div>

            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalShift">
                <i class="ri-add-line me-1"></i> Tambah Shift
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
        <div class="card">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Nama Shift</th>
                            <th>Jam Masuk</th>
                            <th>Jam Keluar</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($shifts as $shift)
                            <tr>
                                <td>{{ $shift->nama_shift }}</td>
                                <td>{{ $shift->jam_masuk }}</td>
                                <td>{{ $shift->jam_keluar }}</td>
                                <td class="text-end">

                                    <!-- EDIT -->
                                    <button class="btn btn-sm btn-icon" data-bs-toggle="modal"
                                        data-bs-target="#editShift{{ $shift->id }}">
                                        <i class="ri-pencil-line"></i>
                                    </button>

                                    <!-- DELETE -->
                                    <button class="btn btn-sm btn-icon text-danger btn-delete"
                                        data-id="{{ $shift->id }}">
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

    <!-- ================= MODAL TAMBAH ================= -->
    <div class="modal fade" id="modalShift" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('admin.shift.store') }}" method="POST" class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Tambah Shift</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Nama Shift</label>
                        <input type="text" name="nama_shift"
                            class="form-control @error('nama_shift') is-invalid @enderror" placeholder="Contoh: Shift Pagi"
                            value="{{ old('nama_shift') }}">
                        @error('nama_shift')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jam Masuk</label>
                        <input type="time" name="jam_masuk" class="form-control @error('jam_masuk') is-invalid @enderror"
                            value="{{ old('jam_masuk') }}">
                        @error('jam_masuk')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Jam Keluar</label>
                        <input type="time" name="jam_keluar"
                            class="form-control @error('jam_keluar') is-invalid @enderror" value="{{ old('jam_keluar') }}">
                        @error('jam_keluar')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                    <button class="btn btn-primary">Simpan</button>
                </div>

            </form>
        </div>
    </div>

    <!-- ================= MODAL EDIT ================= -->
    @foreach ($shifts as $shift)
        <div class="modal fade" id="editShift{{ $shift->id }}" tabindex="-1">
            <div class="modal-dialog">
                <form action="{{ route('admin.shift.update', $shift->id) }}" method="POST" class="modal-content">
                    @csrf
                    @method('PUT')

                    <div class="modal-header">
                        <h5 class="modal-title">Edit Shift</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>

                    <div class="modal-body">

                        <div class="mb-3">
                            <label class="form-label">Nama Shift</label>
                            <input type="text" name="nama_shift" class="form-control" value="{{ $shift->nama_shift }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jam Masuk</label>
                            <input type="time" name="jam_masuk" class="form-control" value="{{ $shift->jam_masuk }}"
                                required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Jam Keluar</label>
                            <input type="time" name="jam_keluar" class="form-control" value="{{ $shift->jam_keluar }}"
                                required>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                        <button class="btn btn-primary">Update</button>
                    </div>

                </form>
            </div>
        </div>
    @endforeach

    <!-- ================= SCRIPT DELETE ================= -->
    <script>
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                let id = this.getAttribute('data-id');

                Swal.fire({
                    title: 'Yakin hapus?',
                    text: "Data shift akan dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#696cff',
                    cancelButtonColor: '#8592a3',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-shift-' + id).submit();
                    }
                });
            });
        });
    </script>
@endsection
