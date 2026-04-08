<!doctype html>

<html lang="en" class="layout-menu-fixed layout-compact" data-assets-path="../assets/"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />

    <title>Parkir System</title>

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
            @yield('content')
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
</body>

</html>
