<!doctype html>

<html lang="en" class="layout-wide customizer-hide" data-assets-path="{{ asset('assets/') }}"
    data-template="vertical-menu-template-free">

<head>
    <meta charset="utf-8" />
    <meta name="viewport"
        content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <meta name="robots" content="noindex, nofollow" />

    <title>Gavin Mukti K | Parkir System </title>

    <meta name="description" content="" />

    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/favicon/Favicon.png') }}" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&ampdisplay=swap"
        rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@600;700&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/iconify-icons.css') }}" />

    <!-- Core CSS -->
    <!-- build:css assets/vendor/css/theme.css -->

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/node-waves/node-waves.css') }}" />

    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />

    <!-- Vendors CSS -->

    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />

    <!-- endbuild -->

    <!-- Page CSS -->
    <!-- Page -->
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/page-auth.css') }}" />

    <!-- Helpers -->
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <!--! Template customizer & Theme config files MUST be included after core stylesheets and helpers.js in the <head> section -->

    <!--? Config: Mandatory theme config file contain global vars & default theme options, Set your preferred theme option in this file. -->

    <script src="{{ asset('assets/js/config.js') }}"></script>
</head>

{{-- CSS TXT LOGO --}}
<style>
    .logo-text {
        font-family: 'Poppins', sans-serif;
        letter-spacing: 2px;
        font-weight: 700;
        font-size: 26px;
    }
</style>

<body>
    <!-- Content -->

    <div class="position-relative">
        <div class="authentication-wrapper authentication-basic">
            <div class="authentication-inner py-3 mx-4">
                <!-- Login -->
                <div class="card p-sm-7 p-2">
                    <!-- Logo -->
                    <div class="app-brand justify-content-center mt-2 mb-2">
                        <a href="{{ url('/') }}"
                            class="app-brand-link d-flex flex-column align-items-center gap-2">

                            <img src="{{ asset('assets/img/Logo-parkir.png') }}" width="64" />

                            <span class="logo-text text-primary">
                                PARKIR SYSTEM
                            </span>

                        </a>
                    </div>
                    <!-- /Logo -->

                    <div class="card-body pt-1">
                        <h4 class="mb-1 text-center">Selamat Datang di Aplikasi Parkir</h4>
                        <p class="mb-3 text-center">
                            Silakan login sesuai hak akses Anda
                        </p>
                        <form id="formAuthentication" class="mb-5" method="POST" action="{{ route('login-proses') }}">
                            @csrf

                            <div class="form-floating form-floating-outline mb-5">
                                <input type="text" class="form-control" name="login"
                                    placeholder="Email atau Username" autofocus />
                                <label>Email atau Username</label>
                            </div>

                            <div class="mb-5">
                                <div class="input-group input-group-merge">
                                    <div class="form-floating form-floating-outline">
                                        <input type="password" class="form-control" name="password"
                                            placeholder="Password" />
                                        <label>Password</label>
                                    </div>
                                </div>
                            </div>

                            <div class="mb-5">
                                <button class="btn btn-primary d-grid w-100" type="submit">
                                    Masuk Ke Sistem
                                </button>
                            </div>
                        </form>


                    </div>
                </div>
                <!-- /Login -->
                <img src="{{ asset('assets/img/illustrations/tree-3.png') }}" alt="auth-tree"
                    class="authentication-image-object-left d-none d-lg-block" />
                <img src="{{ asset('assets/img/illustrations/auth-basic-mask-light.png') }}"
                    class="authentication-image d-none d-lg-block scaleX-n1-rtl" height="172" alt="triangle-bg" />
                <img src="{{ asset('assets/img/illustrations/tree.png') }}" alt="auth-tree"
                    class="authentication-image-object-right d-none d-lg-block" />
            </div>
        </div>
    </div>

    <!-- / Content -->

    <!-- Core JS -->

    <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
    <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/node-waves/node-waves.js') }}"></script>

    <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>

    <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>

    <!-- endbuild -->

    <!-- Vendors JS -->

    <!-- Main JS -->

    <script src="../assets/js/main.js"></script>

    <!-- Page JS -->

    <!-- Place this tag before closing body tag for github widget button. -->
    <script async="async" defer="defer" src="https://buttons.github.io/buttons.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Login Gagal',
                text: '{{ session('error') }}',
                confirmButtonText: 'Coba Lagi'
            });
        </script>
    @endif

    @if (session('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: 'Berhasil',
                text: '{{ session('success') }}',
                confirmButtonText: 'OK'
            });
        </script>
    @endif
</body>

</html>
