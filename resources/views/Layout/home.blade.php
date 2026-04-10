<!doctype html>

<html lang="en" class="layout-menu-fixed layout-compact" data-assets-path="../assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />

    <title>Gavin Mukti K | Parkir System</title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/Favicon.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
        rel="stylesheet" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}" />

    <!-- Core CSS -->
    <!-- build:css assets/vendor/css/theme.css -->

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- endbuild -->

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/apex-charts/apex-charts.css') }}" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon/fonts/remixicon.css" rel="stylesheet">

    <!-- Page CSS -->

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Config: Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file. -->

    <script src="{{ asset('assets/js/config.js') }}"></script>
</head>
@yield('css')

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->


            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="{{ route('admin.dashboard') }}" class="app-brand-link">
                        <img src="{{ asset('assets/img/Logo-parkir.png') }}" width="30" alt="Logo Parkir">
                        <span class="app-brand-text demo menu-text fw-semibold ms-2">PARKIR SYSTEM</span>
                    </a>


                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">


                    {{-- Admin Menu --}}
                    @if (Auth::check() && Auth::user()->role == 'admin')
                        <li class="menu-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('admin.dashboard') }}" class="menu-link">
                                <i class="menu-icon ri ri-home-4-line"></i>
                                <div data-i18n="Dashboard">Dashboard</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->routeIs('admin.users') ? 'active' : '' }}">
                            <a href="{{ route('admin.users') }}" class="menu-link">
                                <i class="menu-icon ri ri-user-3-line"></i>
                                <div data-i18n="Kelola Data User">Kelola Data User</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->routeIs('admin.shift') ? 'active' : '' }}">
                            <a href="{{ route('admin.shift') }}" class="menu-link">
                                <i class="menu-icon ri ri-timer-line"></i>
                                <div data-i18n="Kelola Shift">Kelola Shift</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->routeIs('admin.tarif') ? 'active' : '' }}">
                            <a href="{{ route('admin.tarif') }}" class="menu-link">
                                <i class="menu-icon ri ri-money-dollar-box-line"></i>
                                <div data-i18n="Kelola Tarif">Kelola Tarif</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->routeIs('admin.area') ? 'active' : '' }}">
                            <a href="{{ route('admin.area') }}" class="menu-link">
                                <i class="menu-icon ri ri-map-2-line"></i>
                                <div data-i18n="Kelola Area">Kelola Area</div>
                            </a>
                        </li>
                        <li class="menu-item {{ request()->routeIs('admin.log') ? 'active' : '' }}">
                            <a href="{{ route('admin.log') }}" class="menu-link">
                                <i class="menu-icon ri ri-file-list-3-line"></i>
                                <div data-i18n="Log Aktivitas">Log Aktivitas</div>
                            </a>
                        </li>
                    @endif

                    {{-- Petugas Menu --}}
                    @if (Auth::check() && Auth::user()->role == 'petugas')
                        <li class="menu-item {{ request()->routeIs('petugas.dashboard') ? 'active' : '' }}">
                            <a href="{{ route('petugas.dashboard') }}" class="menu-link">
                                <i class="menu-icon ri ri-home-4-line"></i>
                                <div data-i18n="Dashboard">Dashboard</div>
                            </a>
                        </li>
                        <li class="menu-item ">
                            <a href="{{ route('petugas.transaksi.index') }}" class="menu-link">
                                <i class="menu-icon ri ri-shopping-cart-line"></i>
                                <div data-i18n="Transaksi">Transaksi</div>
                            </a>
                        </li>
                    @endif

                    {{-- Owner Menu --}}
                    @if (Auth::check() && Auth::user()->role == 'owner')
                        <li class="menu-item active">
                            <a href="{{ route('owner.dashboard') }}" class="menu-link">
                                <i class="menu-icon ri ri-home-4-line"></i>
                                <div data-i18n="Dashboard">Dashboard</div>
                            </a>
                        </li>
                        <li class="menu-item">
                            <a href="{{ route('owner.rekap') }}" class="menu-link">
                                <i class="menu-icon ri ri-file-list-3-line"></i>
                                <div data-i18n="Rekap Transaksi">Rekap Transaksi</div>
                            </a>
                        </li>
                    @endif

                </ul>

            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->

                <nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme"
                    id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
                            <i class="icon-base ri ri-menu-line icon-md"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">

                        <ul class="navbar-nav flex-row align-items-center ms-md-auto">
                            <li class="nav-item lh-1 me-4 d-flex align-items-center">
                                <span class="text-muted me-1">Hi,</span>
                                <span class="fw-semibold me-2">{{ Auth::user()->username }}</span>
                                <span class="badge bg-label-primary">
                                    {{ ucfirst(Auth::user()->role) }}
                                </span>
                            </li>
                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown-user dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);"
                                    data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=random"
                                            class="rounded-circle" />
                                    </div>
                                </a>

                                <ul class="dropdown-menu dropdown-menu-end">

                                    <!-- PROFILE INFO -->
                                    <li>
                                        <div class="dropdown-item">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->username) }}&background=random"
                                                            class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0">{{ Auth::user()->username }}</h6>
                                                    <small class="text-body-secondary">
                                                        {{ ucfirst(Auth::user()->role) }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </li>

                                    <li>
                                        <div class="dropdown-divider my-0"></div>
                                    </li>
                                    <!-- LOGOUT -->
                                    <li>
                                        <div class="d-grid px-4 pt-2 pb-1">
                                            <form action="{{ route('logout') }}" method="POST">
                                                @csrf
                                                <button class="btn btn-danger d-flex w-100">
                                                    <small class="align-middle">Logout</small>
                                                    <i class="ri ri-logout-box-r-line ms-2 ri-xs"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </li>

                                </ul>
                            </li>
                            <!--/ User -->

                        </ul>
                    </div>
                </nav>

                <!-- / Navbar -->

                <!-- Content wrapper -->


                @yield('content')


                <!-- Content wrapper -->
            </div>

            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->



    <!-- Core JS -->

    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>

    <!-- Main JS -->

    <script src="{{ asset('assets/js/main.js') }}"></script>

    <!-- Page JS -->
    <script src="{{ asset('assets/js/dashboards-analytics.js') }}"></script>

    <!-- Place this tag before closing body tag for github widget button. -->
    <script async="async" defer="defer" src="https://buttons.github.io/buttons.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: "{{ session('success') }}",
                timer: 2000,
                showConfirmButton: false
            });
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: "{{ session('error') }}"
            });
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: 'Form tidak boleh kosong / ada yang salah!'
            });
        </script>
    @endif

    <script>
        document.querySelectorAll('.btn-delete').forEach(button => {
            button.addEventListener('click', function() {
                let id = this.getAttribute('data-id');

                Swal.fire({
                    title: 'Yakin?',
                    text: "Data akan dihapus!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.querySelector('#delete-' + id)?.submit();
                        document.querySelector('#delete-shift-' + id)?.submit();
                    }
                });
            });
        });
    </script>
</body>

</html>
