@extends('Layout.home')

@section('content')
<div class="layout-wrapper layout-content-navbar">
  <div class="layout-container">
    <!-- Layout page -->
    <div class="layout-page">
      <!-- Navbar -->
      <nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
        <!-- Navbar content (biarkan seperti sekarang) -->
      </nav>
      <!-- / Navbar -->

      <!-- Content wrapper -->
      <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">

          <!-- Row Statistik -->
          <div class="row gy-4 mb-4">
            <!-- Parkir Aktif -->
            <div class="col-md-4">
              <div class="card text-white bg-primary mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                  <div>
                    <h6 class="card-title">Parkir Aktif</h6>
                    <h3 class="mb-0">{{ $parkirAktif }}</h3>
                  </div>
                  <i class="ri-car-line ri-3x"></i>
                </div>
              </div>
            </div>

            <!-- Total Transaksi Hari Ini -->
            <div class="col-md-4">
              <div class="card text-white bg-success mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                  <div>
                    <h6 class="card-title">Total Transaksi Hari Ini</h6>
                    <h3 class="mb-0">{{ $totalTransaksi }}</h3>
                  </div>
                  <i class="ri-file-list-3-line ri-3x"></i>
                </div>
              </div>
            </div>

            <!-- Pendapatan Hari Ini -->
            <div class="col-md-4">
              <div class="card text-white bg-warning mb-3">
                <div class="card-body d-flex justify-content-between align-items-center">
                  <div>
                    <h6 class="card-title">Pendapatan Hari Ini</h6>
                    <h3 class="mb-0">Rp {{ number_format($pendapatanHariIni, 0, ',', '.') }}</h3>
                  </div>
                  <i class="ri-money-dollar-circle-line ri-3x"></i>
                </div>
              </div>
            </div>
          </div>
          <!-- /Row Statistik -->

          <!-- Tabel User -->
          <div class="card mt-4">
            <div class="card-header">
              <h5>Daftar User</h5>
            </div>
            <div class="card-body table-responsive">
              <table class="table table-striped table-sm">
                <thead>
                  <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($users as $user)
                  <tr>
                    <td>{{ $user->id }}</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ ucfirst($user->role) }}</td>
                    <td>
                      @if($user->status_aktif)
                        <span class="badge bg-label-success rounded-pill">Aktif</span>
                      @else
                        <span class="badge bg-label-secondary rounded-pill">Nonaktif</span>
                      @endif
                    </td>
                  </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
          </div>
          <!-- /Tabel User -->

        </div>
        <!-- /Content -->

        <div class="content-backdrop fade"></div>
      </div>
      <!-- /Content wrapper -->
    </div>
    <!-- / Layout page -->
  </div>

  <!-- Overlay -->
  <div class="layout-overlay layout-menu-toggle"></div>
</div>
@endsection