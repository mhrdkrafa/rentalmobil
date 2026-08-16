<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>@yield('page_title', 'Dashboard') - AutoRent Admin</title>

  <!-- Tabler CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/core@1.2.0/dist/css/tabler.min.css">
  <!-- Tabler Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@3.31.0/dist/tabler-icons.min.css">

  <style>
    .navbar-brand-image { height: 2rem; }
    .page-pretitle { text-transform: uppercase; font-size: 0.7rem; letter-spacing: 0.04em; }
  </style>
  @stack('styles')
</head>
<body class="layout-fluid">
  <script src="https://cdn.jsdelivr.net/npm/@tabler/core@1.2.0/dist/js/tabler.min.js"></script>
  <div class="page">
    <!-- Sidebar -->
    <aside class="navbar navbar-vertical navbar-expand-lg" data-bs-theme="dark">
      <div class="container-fluid">
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#sidebar-menu" aria-controls="sidebar-menu" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <h1 class="navbar-brand navbar-brand-autodark">
          <a href="{{ route('admin.dashboard') }}" class="text-decoration-none">
            <span class="text-white fw-bold fs-3">Auto<span class="text-primary">Rent</span></span>
          </a>
        </h1>

        <div class="navbar-nav flex-row d-lg-none">
          <div class="nav-item dropdown">
            <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
              <span class="avatar avatar-sm rounded-circle bg-primary text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-end">
              <a class="dropdown-item" href="{{ route('profile.edit') }}">Profil</a>
              <div class="dropdown-divider"></div>
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="dropdown-item text-danger">Logout</button>
              </form>
            </div>
          </div>
        </div>

        <div class="collapse navbar-collapse" id="sidebar-menu">
          <ul class="navbar-nav pt-lg-3">

            {{-- Dashboard --}}
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                <span class="nav-link-icon"><i class="ti ti-dashboard"></i></span>
                <span class="nav-link-title">Dashboard</span>
              </a>
            </li>

            {{-- Armada --}}
            <li class="nav-item dropdown {{ request()->is('admin/vehicle*') ? 'active' : '' }}">
              <a class="nav-link dropdown-toggle" href="#navbar-armada" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ request()->is('admin/vehicle*') ? 'true' : 'false' }}">
                <span class="nav-link-icon"><i class="ti ti-car"></i></span>
                <span class="nav-link-title">Armada</span>
              </a>
              <div class="dropdown-menu {{ request()->is('admin/vehicle*') ? 'show' : '' }}">
                <a class="dropdown-item {{ request()->routeIs('admin.vehicle-categories.*') ? 'active' : '' }}" href="{{ route('admin.vehicle-categories.index') }}">
                  Kategori Mobil
                </a>
                <a class="dropdown-item {{ request()->routeIs('admin.vehicles.*') ? 'active' : '' }}" href="{{ route('admin.vehicles.index') }}">
                  Daftar Mobil
                </a>
              </div>
            </li>

            <li class="nav-item nav-item-separator my-1">
              <hr class="m-0 opacity-25">
            </li>
            <li class="nav-item"><small class="nav-link-title text-uppercase text-muted px-3 pt-2 pb-1" style="font-size:.65rem; letter-spacing:.06em;">Manajemen</small></li>

            {{-- Booking --}}
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('admin.bookings.*') ? 'active' : '' }}" href="{{ route('admin.bookings.index') }}">
                <span class="nav-link-icon"><i class="ti ti-calendar-event"></i></span>
                <span class="nav-link-title">Booking</span>
              </a>
            </li>

            {{-- Payments --}}
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('admin.payments.*') ? 'active' : '' }}" href="{{ route('admin.payments.index') }}">
                <span class="nav-link-icon"><i class="ti ti-credit-card"></i></span>
                <span class="nav-link-title">Pembayaran</span>
              </a>
            </li>

            {{-- Customer --}}
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('admin.customers.*') ? 'active' : '' }}" href="{{ route('admin.customers.index') }}">
                <span class="nav-link-icon"><i class="ti ti-users"></i></span>
                <span class="nav-link-title">Customer</span>
              </a>
            </li>

            {{-- Review --}}
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('admin.reviews.*') ? 'active' : '' }}" href="{{ route('admin.reviews.index') }}">
                <span class="nav-link-icon"><i class="ti ti-star"></i></span>
                <span class="nav-link-title">Review</span>
              </a>
            </li>

            {{-- Laporan --}}
            <li class="nav-item">
              <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" href="{{ route('admin.reports.index') }}">
                <span class="nav-link-icon"><i class="ti ti-chart-bar"></i></span>
                <span class="nav-link-title">Laporan</span>
              </a>
            </li>

            <li class="nav-item nav-item-separator my-1">
              <hr class="m-0 opacity-25">
            </li>
            <li class="nav-item"><small class="nav-link-title text-uppercase text-muted px-3 pt-2 pb-1" style="font-size:.65rem; letter-spacing:.06em;">Sistem</small></li>

            {{-- Pengaturan --}}
            <li class="nav-item dropdown {{ request()->routeIs('admin.settings.*') || request()->routeIs('admin.notification-logs.*') ? 'active' : '' }}">
              <a class="nav-link dropdown-toggle" href="#navbar-settings" data-bs-toggle="dropdown" data-bs-auto-close="false" role="button" aria-expanded="{{ request()->routeIs('admin.settings.*') || request()->routeIs('admin.notification-logs.*') ? 'true' : 'false' }}">
                <span class="nav-link-icon"><i class="ti ti-settings"></i></span>
                <span class="nav-link-title">Pengaturan</span>
              </a>
              <div class="dropdown-menu {{ request()->routeIs('admin.settings.*') || request()->routeIs('admin.notification-logs.*') ? 'show' : '' }}">
                <a class="dropdown-item {{ request()->routeIs('admin.settings.notifications') ? 'active' : '' }}" href="{{ route('admin.settings.notifications') }}">
                  Notifikasi
                </a>
                <a class="dropdown-item {{ request()->routeIs('admin.notification-logs.*') ? 'active' : '' }}" href="{{ route('admin.notification-logs.index') }}">
                  Log Notifikasi
                </a>
              </div>
            </li>

          </ul>
        </div>
      </div>
    </aside>

    <!-- Main Content -->
    <div class="page-wrapper">
      <!-- Top Navbar -->
      <header class="navbar navbar-expand-md d-none d-lg-flex d-print-none">
        <div class="container-xl">
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>

          <div class="navbar-nav flex-row order-md-last">
            <div class="nav-item dropdown">
              <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Open user menu">
                <span class="avatar avatar-sm rounded-circle bg-primary text-white">{{ substr(Auth::user()->name, 0, 1) }}</span>
                <div class="d-none d-xl-block ps-2">
                  <div>{{ Auth::user()->name }}</div>
                  <div class="mt-1 small text-secondary">{{ ucfirst(Auth::user()->role->value) }}</div>
                </div>
              </a>
              <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
                <a href="{{ route('profile.edit') }}" class="dropdown-item">Profil</a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                  @csrf
                  <button type="submit" class="dropdown-item text-danger">
                    <i class="ti ti-logout me-1"></i> Logout
                  </button>
                </form>
              </div>
            </div>
          </div>
        </div>
      </header>

      <!-- Page Header -->
      <div class="page-header d-print-none">
        <div class="container-xl">
          <div class="page-pretitle">@yield('page_pretitle', 'Overview')</div>
          <h2 class="page-title">@yield('page_title', 'Dashboard')</h2>
        </div>
      </div>

      <!-- Page Body -->
      <div class="page-body">
        <div class="container-xl">

          {{-- Flash Messages --}}
          @if(session('success'))
          <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex">
              <div><i class="ti ti-check icon alert-icon"></i></div>
              <div>{{ session('success') }}</div>
            </div>
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
          </div>
          @endif

          @if(session('error'))
          <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex">
              <div><i class="ti ti-alert-circle icon alert-icon"></i></div>
              <div>{{ session('error') }}</div>
            </div>
            <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
          </div>
          @endif

          @yield('content')

        </div>
      </div>

      <!-- Footer -->
      <footer class="footer footer-transparent d-print-none">
        <div class="container-xl">
          <div class="row text-center align-items-center flex-row-reverse">
            <div class="col-auto ms-auto">
              <span class="text-secondary">v1.0.0</span>
            </div>
            <div class="col-12 col-lg-auto mt-3 mt-lg-0">
              Copyright &copy; {{ date('Y') }} <a href="{{ route('public.home') }}" class="link-secondary">AutoRent</a>. All rights reserved.
            </div>
          </div>
        </div>
      </footer>
    </div>
  </div>

  <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>

  @stack('scripts')
</body>
</html>
