@extends('Layout.home')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">

        <!-- HEADER -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-0">Manajemen User</h4>
                <small class="text-muted">Kelola pengguna sistem parkir</small>
            </div>

            <!-- BUTTON TAMBAH -->
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalUser">
                <i class="ri-add-line me-1"></i> Tambah User
            </button>
        </div>

        <div class="col-12">
            <div class="card overflow-hidden">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th class="text-truncate">User</th>

                                <th class="text-truncate">Role</th>
                                <th class="text-truncate">Status</th>
                                <th class="text-truncate text-end">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @foreach ($users as $user)
                                <tr>

                                    <!-- USER -->
                                    <td>
                                        <div class="d-flex align-items-center">

                                            <!-- AVATAR -->
                                            <div class="avatar avatar-sm me-4">
                                                <div class="avatar avatar-sm me-4">
                                                    <img src="https://ui-avatars.com/api/?name={{ urlencode($user->username) }}&background=random"
                                                        class="rounded-circle" />
                                                </div>
                                            </div>

                                            <!-- NAME -->
                                            <div>
                                                <h6 class="mb-0 text-truncate">
                                                    {{ $user->username }}
                                                </h6>
                                                <small class="text-truncate text-muted">
                                                    {{ $user->email }}
                                                </small>
                                            </div>

                                        </div>
                                    </td>



                                    <!-- ROLE -->
                                    <td class="text-truncate">
                                        <div class="d-flex align-items-center">

                                            @if ($user->role == 'admin')
                                                <i class="icon-base ri ri-vip-crown-line icon-22px text-primary me-2"></i>
                                            @elseif($user->role == 'petugas')
                                                <i class="icon-base ri ri-shield-user-line icon-22px text-warning me-2"></i>
                                            @else
                                                <i class="icon-base ri ri-user-3-line icon-22px text-info me-2"></i>
                                            @endif

                                            <span>{{ ucfirst($user->role) }}</span>
                                        </div>
                                    </td>

                                    <!-- STATUS -->
                                    <td>
                                        @if ($user->status_aktif)
                                            <span class="badge bg-label-success rounded-pill">
                                                Active
                                            </span>
                                        @else
                                            <span class="badge bg-label-secondary rounded-pill">
                                                Inactive
                                            </span>
                                        @endif
                                    </td>

                                    <td class="text-end">

                                        <!-- EDIT BUTTON -->
                                        <button class="btn btn-sm btn-icon" data-bs-toggle="modal"
                                            data-bs-target="#editUser{{ $user->id }}">
                                            <i class="ri-pencil-line"></i>
                                        </button>

                                        <!-- DELETE BUTTON -->
                                        <button class="btn btn-sm btn-icon text-danger btn-delete"
                                            data-id="{{ $user->id }}">
                                            <i class="ri-delete-bin-line"></i>
                                        </button>

                                        <!-- FORM DELETE (hidden) -->
                                        <form id="delete-form-{{ $user->id }}"
                                            action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                            style="display:none;">
                                            @csrf
                                            @method('DELETE')
                                        </form>

                                    </td>

                                </tr>
                            @endforeach
                        </tbody>

                    </table>

                    @foreach ($users as $user)
                        <div class="modal fade" id="editUser{{ $user->id }}" tabindex="-1">
                            <div class="modal-dialog">
                                <form action="{{ route('admin.users.update', $user->id) }}" method="POST"
                                    class="modal-content">
                                    @csrf
                                    @method('PUT')

                                    <div class="modal-header">
                                        <h5 class="modal-title">Edit User</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                    </div>

                                    <div class="modal-body">

                                        <div class="mb-3">
                                            <label class="form-label">Username</label>
                                            <input type="text" name="username"
                                                class="form-control @error('username') is-invalid @enderror"
                                                placeholder="Masukkan username"
                                                value="{{ old('username', $user->username) }}">

                                            @error('username')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Email</label>
                                            <input type="email" name="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                placeholder="contoh@email.com" value="{{ old('email', $user->email) }}">

                                            @error('email')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Password (jika lupa saja)</label>
                                            <input type="password" name="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                placeholder="Kosongkan jika tidak diubah">

                                            @error('password')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label">Role</label>
                                            <select name="role" class="form-select @error('role') is-invalid @enderror">
                                                <option value="petugas"
                                                    {{ old('role', $user->role) == 'petugas' ? 'selected' : '' }}>Petugas
                                                </option>
                                                <option value="owner"
                                                    {{ old('role', $user->role) == 'owner' ? 'selected' : '' }}>Owner
                                                </option>
                                            </select>

                                            @error('role')
                                                <small class="text-danger">{{ $message }}</small>
                                            @enderror
                                        </div>
                                        @if ($errors->any())
                                            <script>
                                                var myModal = new bootstrap.Modal(document.getElementById('modalUser'));
                                                myModal.show();
                                            </script>
                                        @endif
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-label-secondary"
                                            data-bs-dismiss="modal">Batal</button>
                                        <button type="submit" class="btn btn-primary">Update</button>
                                    </div>

                                </form>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

    </div>

    <!-- MODAL TAMBAH USER -->
    <div class="modal fade" id="modalUser" tabindex="-1">
        <div class="modal-dialog">
            <form action="{{ route('admin.users.store') }}" method="POST" class="modal-content">
                @csrf

                <div class="modal-header">
                    <h5 class="modal-title">Tambah User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" name="username"
                            class="form-control @error('username') is-invalid @enderror"
                            placeholder="Masukkan username (min 3 karakter)" value="{{ old('username') }}">

                        @error('username')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-control @error('email') is-invalid @enderror"
                            placeholder="contoh@email.com" value="{{ old('email') }}">

                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Password</label>
                        <input type="password" name="password"
                            class="form-control @error('password') is-invalid @enderror" placeholder="Minimal 6 karakter">

                        @error('password')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Role</label>
                        <select name="role" class="form-select @error('role') is-invalid @enderror">
                            <option value="">-- Pilih Role --</option>
                            <option value="petugas" {{ old('role') == 'petugas' ? 'selected' : '' }}>Petugas</option>
                            <option value="owner" {{ old('role') == 'owner' ? 'selected' : '' }}>Owner</option>
                        </select>

                        @error('role')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </div>

            </form>
            @if ($errors->any())
                <script>
                    var myModal = new bootstrap.Modal(document.getElementById('modalUser'));
                    myModal.show();
                </script>
            @endif
        </div>
    </div>


    <script>
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {

                let id = this.getAttribute('data-id');

                Swal.fire({
                    title: 'Yakin hapus?',
                    text: "Data user akan dihapus permanen!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#696cff',
                    cancelButtonColor: '#8592a3',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + id).submit();
                    }
                });

            });
        });
    </script>
@endsection
