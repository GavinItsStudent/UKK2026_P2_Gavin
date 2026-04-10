@extends('Layout.home')

@section('content')
    <div class="container-xxl container-p-y">

        <h4 class="fw-bold mb-4">Kelola Kendaraan</h4>

        <!-- FORM -->
        <div class="card p-3 mb-4">
            <form action="{{ route('admin.kendaraan.store') }}" method="POST" class="row g-3">
                @csrf

                <div class="col-md-3">
                    <input type="text" name="plat_nomor" class="form-control" placeholder="Plat Nomor">
                </div>

                <div class="col-md-3">
                    <select name="jenis_kendaraan" class="form-select">
                        <option value="motor">Motor</option>
                        <option value="mobil">Mobil</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <input type="text" name="warna" class="form-control" placeholder="Warna">
                </div>


                <div class="col-md-1">
                    <button class="btn btn-primary w-100">+</button>
                </div>
            </form>
        </div>

        <!-- TABLE -->
        <div class="card">
            <div class="table-responsive">
                <table class="table table-sm">
                    <thead>
                        <tr>
                            <th>Plat</th>
                            <th>Jenis</th>
                            <th>Warna</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($kendaraans as $k)
                            <tr>
                                <td>{{ $k->plat_nomor }}</td>
                                <td>{{ $k->jenis_kendaraan }}</td>
                                <td>{{ $k->warna }}</td>
                                <td class="text-end">

                                    <button class="btn btn-sm btn-danger btn-delete" data-id="{{ $k->id }}">
                                        Hapus
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
            </div>
        </div>

    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.btn-delete').forEach(btn => {
                btn.addEventListener('click', function() {
                    let id = this.getAttribute('data-id');

                    Swal.fire({
                        title: 'Yakin hapus?',
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonText: 'Ya'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            document.getElementById('delete-kendaraan-' + id).submit();
                        }
                    });
                });
            });
        });
    </script>
@endsection
