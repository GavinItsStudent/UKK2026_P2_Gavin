@extends('Layout.home')

@section('content')
    <div class="container-xxl container-p-y">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-0">Kelola User</h4>
                <small class="text-muted">Manajemen pengguna sistem</small>
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
            <form action="{{ route('admin.users.store') }}" method="POST" class="row g-3">
                @csrf

                <div class="col-md-3">
                    <label class="form-label">Username</label>
                    <input type="text" name="username" value="{{ old('username') }}" class="form-control"
                        placeholder="Masukan Username">
                </div>

                <div class="col-md-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control"
                        placeholder="Masukan Email">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Password</label>
                    <input type="password" name="password" class="form-control" placeholder="Masukan Password">
                </div>

                <div class="col-md-2">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select">
                        <option value="">-- Pilih --</option>
                        <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                        <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>Owner</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label class="form-label">Shift</label>
                    <select name="shift_id" class="form-select">
                        <option value="">-- Pilih --</option>
                        @foreach ($shifts as $shift)
                            <option value="{{ $shift->id }}" {{ old('shift_id') == $shift->id ? 'selected' : '' }}>
                                {{ $shift->nama_shift }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-12 text-end">
                    <button class="btn btn-primary px-4">
                        <i class="ri-add-line me-1"></i> Tambah
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
                            <th>User</th>
                            <th>Role</th>
                            <th>Shift</th>
                            <th>Status</th>
                            <th class="text-end no-print">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($users as $user)
                            <tr>

                                <td>
                                    <div class="d-flex align-items-center">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->username) }}"
                                            class="rounded-circle me-2" width="35">
                                        <div>
                                            <b>{{ $user->username }}</b><br>
                                            <small>{{ $user->email }}</small>
                                        </div>
                                    </div>
                                </td>

                                <td>
                                    <span class="badge bg-info text-dark">
                                        {{ ucfirst($user->role) }}
                                    </span>
                                </td>

                                <td>
                                    {{ optional($user->shift)->nama_shift ?? '-' }}
                                </td>

                                <td>
                                    @if ($user->status_aktif)
                                        <span class="badge bg-success">Active</span>
                                    @else
                                        <span class="badge bg-secondary">Inactive</span>
                                    @endif
                                </td>

                                <td class="text-end no-print">

                                    <button class="btn btn-sm btn-warning" data-bs-toggle="modal"
                                        data-bs-target="#editUser{{ $user->id }}">
                                        <i class="ri-pencil-line"></i>
                                    </button>

                                    <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $user->id }}">
                                        <i class="ri-delete-bin-line"></i>
                                    </button>

                                    <form id="delete-form-{{ $user->id }}"
                                        action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                        style="display:none;">
                                        @csrf
                                        @method('DELETE')
                                    </form>

                                </td>

                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted">
                                    Tidak ada data user
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>

    <!-- MODAL EDIT -->
    @foreach ($users as $user)
        <div class="modal fade" id="editUser{{ $user->id }}">
            <div class="modal-dialog">
                <form action="{{ route('admin.users.update', $user->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="modal-content">

                        <div class="modal-header">
                            <h5 class="modal-title">Edit User</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                        </div>

                        <div class="modal-body">

                            <input type="text" name="username" class="form-control mb-2" value="{{ $user->username }}">

                            <input type="email" name="email" class="form-control mb-2" value="{{ $user->email }}">

                            <input type="password" name="password" class="form-control mb-2"
                                placeholder="Kosongkan jika tidak diubah">

                            <select name="role" class="form-select mb-2">
                                <option value="petugas" {{ $user->role == 'petugas' ? 'selected' : '' }}>Petugas</option>
                                <option value="owner" {{ $user->role == 'owner' ? 'selected' : '' }}>Owner</option>
                            </select>

                            <select name="shift_id" class="form-select mb-2">
                                <option value="">-- Pilih Shift --</option>
                                @foreach ($shifts as $shift)
                                    <option value="{{ $shift->id }}"
                                        {{ $user->shift_id == $shift->id ? 'selected' : '' }}>
                                        {{ $shift->nama_shift }}
                                    </option>
                                @endforeach
                            </select>

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

                    let id = this.dataset.id;

                    Swal.fire({
                        title: 'Yakin hapus user?',
                        icon: 'warning',
                        showCancelButton: true
                    }).then((r) => {
                        if (r.isConfirmed) {
                            document.getElementById('delete-form-' + id).submit();
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
