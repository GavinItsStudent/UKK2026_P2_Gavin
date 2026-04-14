@extends('Layout.home')

@section('content')
    <div class="container-xxl container-p-y">

        {{-- HEADER --}}
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-0">Kelola User</h4>
                <small class="text-muted">Manajemen pengguna sistem</small>
            </div>

            <div class="d-flex gap-2 no-print">
                <select id="printRole" class="form-select">
                    <option value="all">Semua Role</option>
                    <option value="petugas">Petugas</option>
                    <option value="owner">Owner</option>
                </select>
                <button onclick="printUsers()" class="btn btn-outline-secondary">
                    Print
                </button>
            </div>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        {{-- FORM TAMBAH --}}
        <div class="card p-3 mb-4 no-print">
            <form action="{{ route('admin.users.store') }}" method="POST" class="row g-3">
                @csrf
                <div class="col-md-3">
                    <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
                </div>
                <div class="col-md-3">
                    <input type="email" name="email" class="form-control" placeholder="Masukkan email" required>
                </div>
                <div class="col-md-2">
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>
                <div class="col-md-2">
                    <select name="role" class="form-select" required>
                        <option value="">Pilih Role</option>
                        <option value="petugas">Petugas</option>
                        <option value="owner">Owner</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <select name="shift_id" class="form-select">
                        <option value="">Pilih Shift</option>
                        @foreach ($shifts as $shift)
                            <option value="{{ $shift->id }}">{{ $shift->nama_shift }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 text-end">
                    <button class="btn btn-primary">Tambah User</button>
                </div>
            </form>
        </div>

        {{-- TABLE --}}
        <div class="card" id="printArea">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Shift</th>
                            <th>Status</th>
                            <th class="text-end no-print">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr data-role="{{ $user->role }}">
                                <td><b>{{ $user->username }}</b></td>
                                <td>{{ $user->email }}</td>
                                <td>{{ ucfirst($user->role) }}</td>
                                <td>{{ optional($user->shift)->nama_shift ?? '-' }}</td>
                                <td>
                                    @if ($user->status_aktif)
                                        <span class="badge bg-success">Online</span>
                                    @else
                                        <span class="badge bg-secondary">Offline</span>
                                    @endif
                                </td>
                                <td class="text-end no-print">
                                    <button type="button" class="btn btn-sm btn-warning btn-edit"
                                        data-id="{{ $user->id }}" data-username="{{ $user->username }}"
                                        data-email="{{ $user->email }}" data-role="{{ $user->role }}"
                                        data-shift="{{ $user->shift_id }}" data-bs-toggle="modal"
                                        data-bs-target="#editModal">
                                        <i class="ri-pencil-line"></i>
                                    </button>

                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                        class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger"
                                            onclick="return confirm('Yakin hapus user?')">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
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
                        <h5>Edit User</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <input type="text" name="username" id="editUsername" class="form-control mb-2"
                            placeholder="Masukkan username" required>
                        <input type="email" name="email" id="editEmail" class="form-control mb-2"
                            placeholder="Masukkan email" required>
                        <input type="password" name="password" class="form-control mb-2"
                            placeholder="Kosongkan jika tidak diubah">

                        <select name="role" id="editRole" class="form-select mb-2">
                            <option value="petugas">Petugas</option>
                            <option value="owner">Owner</option>
                        </select>

                        <select name="shift_id" id="editShift" class="form-select">
                            <option value="">Pilih Shift</option>
                            @foreach ($shifts as $shift)
                                <option value="{{ $shift->id }}">{{ $shift->nama_shift }}</option>
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

    <div id="printOnly"></div>

    <script>
        function printUsers() {
            let role = document.getElementById('printRole').value;
            let rows = document.querySelectorAll('#printArea tbody tr');

            let content = `
        <h3 style="text-align:center;margin-bottom:20px;">Laporan Data User (${role.toUpperCase()})</h3>
        <table border="1" cellspacing="0" cellpadding="8" width="100%">
        <tr>
            <th>Username</th>
            <th>Email</th>
            <th>Role</th>
            <th>Shift</th>
            <th>Status</th>
        </tr>`;

            rows.forEach(row => {
                if (role === 'all' || row.dataset.role === role) {
                    let cols = row.querySelectorAll('td');
                    content += `<tr>
                <td>${cols[0].innerText}</td>
                <td>${cols[1].innerText}</td>
                <td>${cols[2].innerText}</td>
                <td>${cols[3].innerText}</td>
                <td>${cols[4].innerText}</td>
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
            const editForm = document.getElementById('editForm');

            document.querySelectorAll('.btn-edit').forEach(btn => {
                btn.addEventListener('click', function() {
                    let id = this.dataset.id;
                    editForm.action = `/admin/users/${id}`;

                    editUsername.value = this.dataset.username;
                    editEmail.value = this.dataset.email;
                    editRole.value = this.dataset.role;
                    editShift.value = this.dataset.shift ?? '';
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
