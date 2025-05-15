<!doctype html>
<html lang="en" class="light-style layout-menu-fixed layout-compact" dir="ltr" data-theme="theme-default" data-assets-path="../assets/" data-template="vertical-menu-template-free" data-style="light">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title></title>
    <meta name="description" content="" />
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logobem.png') }}" />
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/fonts/boxicons.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/core.css') }}" class="template-customizer-core-css" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/theme-default.css') }}" class="template-customizer-theme-css" />
    <link rel="stylesheet" href="{{ asset('assets/css/demo.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.css') }}" />
    <script src="{{ asset('assets/vendor/js/helpers.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.locatecontrol@0.76.0/dist/L.Control.Locate.min.js"></script>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.locatecontrol@0.76.0/dist/L.Control.Locate.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.5/font/bootstrap-icons.min.css">
</head>

<body>
    <style>
        .custom-button {
            background-color: #758694;
            color: #FFF8F3;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .custom-button:hover {
            background-color: #405D72;
            color: #FFF8F3;
        }

        .app-brand {
            min-height: 180px; /* Sesuaikan dengan ukuran logo */
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .app-brand.demo {
            padding-bottom: 10px;
        }

        .app-brand p {
            font-size: 18px; /* Sesuaikan ukuran teks */
            font-weight: bold;
            color: #000;
            text-align: center;
            white-space: nowrap;
            margin-top: 15px;
        }

    </style>
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <div style="text-align: center; padding: 10px;">
                        <img src="{{ asset('assets/img/logobem.png') }}" alt="Logo" style="height: 150px; width: auto; display: block; margin: 20px auto 0;">
                        <p style="margin-top: 10px; font-size: 16px; font-weight: bold; color: #000;">BEM KM POLIWANGI</p>
                    </div>                    
                    <a href="javascript:void(0);" class="layout-menu-toggle menu-link text-large ms-auto d-block d-xl-none">
                    <i class="bx bx-chevron-left bx-sm d-flex align-items-center justify-content-center"></i>
                    </a>
                </div>
                <div class="menu-inner-shadow"></div>
                <ul class="menu-inner py-1">
                    <li class="menu-header small text-uppercase"><span class="menu-header-text">Data</span></li>
                    <li class="menu-item">
                        <!-- Tambahkan menu Dashboard -->
                        <a href="{{ route('dashboard') }}" class="menu-link mb-2">
                            <i class="menu-icon tf-icons bx bx-home"></i> <!-- Ikon untuk Dashboard -->
                            <div class="text-truncate" data-i18n="Dashboard">Dashboard</div>
                        </a>
                        <a href="{{ route('pemberkasanadmin.index') }}" class="menu-link mb-2">
                            <i class="menu-icon tf-icons bx bx-file"></i> <!-- Ganti ikon jadi dokumen -->
                            <div class="text-truncate" data-i18n="Dashboard">Pemberkasan</div>
                        </a>

                        <!-- Menu Data Kriteria -->
                        <a href="{{ route('criteria.index') }}" class="menu-link mb-2">
                            <i class="menu-icon tf-icons bx bx-chalkboard"></i>
                            <div class="text-truncate" data-i18n="Data Kriteria">Kriteria</div>
                        </a>

                        <!-- Menu Sub Criteria -->
                        <a href="{{ route('sub_criterias.index') }}" class="menu-link mb-2">
                            <i class="menu-icon tf-icons bx bx-list-ul"></i>
                            <div class="text-truncate" data-i18n="Sub Criteria">Sub Kiteria</div>
                        </a>

                        <!-- Menu Alternatives -->
                        <a href="{{ route('alternatives.index') }}" class="menu-link mb-2">
                            <i class="menu-icon tf-icons bx bx-user"></i>
                            <div class="text-truncate" data-i18n="Alternatives">Alternative</div>
                        </a>

                        <!-- Menu Ranking -->
                    {{-- <li class="menu-item dropdown"> --}}
                        <a href="{{ route('ranking.index') }}" class="menu-link mb-2">
                            <i class="menu-icon tf-icons bx bx-trophy"></i>
                            <div class="text-truncate" data-i18n="Rangking">Rangking</div>
                        </a>
                        {{-- <ul class="dropdown-menu">
                            <li>
                                <a href="{{ route('ranking.index') }}" class="dropdown-item">Ranking Awal</a>
                            </li>
                            <li>
                                <a href="{{ route('ranking.keputusan') }}" class="dropdown-item">Keputusan Akhir</a>
                            </li>
                        </ul>
                    </li> --}}

                    </li>
                </ul>
            </aside>
            <div class="layout-page">
                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached align-items-center bg-navbar-theme" id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)"><i class="bx bx-menu bx-md"></i></a>
                    </div>
                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <div class="navbar-nav align-items-center">
                            <div class="nav-item d-flex align-items-center">
                            </div>
                        </div>
                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle hide-arrow" href="#" data-bs-toggle="dropdown">
                                <i class="bx bx-cog fs-4"></i>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                                        <i class="bx bx-key me-2"></i> Ubah Password
                                    </a>
                                </li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" id="logout-form">
                                        @csrf
                                        @method('POST')
                                        <button type="submit" class="dropdown-item">
                                            <i class="bx bx-log-out me-2"></i> Logout
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                        </ul>
                    </div>
                </nav>
                <div class="content-wrapper">
                    @yield('content')
                    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
                    @yield('scripts')
                </div>
            </div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

        <script src="{{ asset('assets/vendor/libs/jquery/jquery.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/popper/popper.js') }}"></script>
        <script src="{{ asset('assets/vendor/js/bootstrap.js') }}"></script>
        <script src="{{ asset('assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js') }}"></script>
        <script src="{{ asset('assets/vendor/js/menu.js') }}"></script>
        <script src="{{ asset('assets/js/main.js') }}"></script>
        <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    </div>
    <!-- Modal Ubah Password -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true">
  <div class="modal-dialog">
        @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    <form method="POST" action="{{ route('password.change') }}">
      @csrf
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="changePasswordModalLabel">Ubah Password</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="current_password" class="form-label">Password Lama</label>
            <input type="password" name="current_password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="new_password" class="form-label">Password Baru</label>
            <input type="password" name="new_password" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
            <input type="password" name="new_password_confirmation" class="form-control" required>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </div>
    </form>
  </div>
</div>
</body>

</html>