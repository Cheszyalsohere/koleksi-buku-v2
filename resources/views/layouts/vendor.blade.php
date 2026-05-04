<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Vendor Panel') - Kantin Online</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">

    <style>
        body { font-family: 'Inter', sans-serif; background: #f1f5f9; }
        .navbar-vendor { background: linear-gradient(135deg, #0f172a 0%, #1e3a5f 100%); }
        .card { border: none; border-radius: 12px; box-shadow: 0 1px 3px rgba(0,0,0,0.08); }
        .card-header { border-radius: 12px 12px 0 0 !important; }
        .sidebar-vendor {
            min-height: calc(100vh - 72px);
            background: #fff;
            border-right: 1px solid #e2e8f0;
        }
        .sidebar-vendor .nav-link {
            color: #475569;
            padding: 10px 20px;
            font-size: 14px;
            font-weight: 500;
            border-left: 3px solid transparent;
            transition: all .15s;
        }
        .sidebar-vendor .nav-link:hover { background: #f1f5f9; color: #1e3a5f; }
        .sidebar-vendor .nav-link.active {
            background: #eff6ff;
            color: #2563eb;
            border-left-color: #2563eb;
            font-weight: 600;
        }
    </style>
    @yield('style-page')
</head>
<body>

    {{-- Navbar --}}
    <nav class="navbar navbar-expand-lg navbar-dark navbar-vendor">
        <div class="container-fluid px-4">
            <a class="navbar-brand fw-bold" href="{{ route('vendor.dashboard') }}">
                <i class="bi bi-shop"></i> Kantin Online — Vendor
            </a>
            <div class="d-flex align-items-center gap-3">
                <span class="text-white-50 small">
                    <i class="bi bi-person-circle"></i>
                    {{ Auth::guard('vendor')->user()->nama_vendor }}
                </span>
                <form id="formLogout" action="{{ route('vendor.logout') }}" method="POST">
                    @csrf
                </form>
                <button type="button" class="btn btn-outline-light btn-sm" onclick="document.getElementById('formLogout').submit();">
                    <i class="bi bi-box-arrow-right"></i> Logout
                </button>
            </div>
        </div>
    </nav>

    <div class="container-fluid">
        <div class="row">
            {{-- Sidebar --}}
            <div class="col-lg-2 col-md-3 p-0">
                <div class="sidebar-vendor py-3">
                    <nav class="nav flex-column">
                        <a class="nav-link {{ request()->routeIs('vendor.dashboard') ? 'active' : '' }}" href="{{ route('vendor.dashboard') }}">
                            <i class="bi bi-speedometer2 me-2"></i> Dashboard
                        </a>
                        <a class="nav-link {{ request()->routeIs('vendor.menu.*') ? 'active' : '' }}" href="{{ route('vendor.menu.index') }}">
                            <i class="bi bi-journal-text me-2"></i> Kelola Menu
                        </a>
                        <a class="nav-link {{ request()->routeIs('vendor.pesanan.lunas') ? 'active' : '' }}" href="{{ route('vendor.pesanan.lunas') }}">
                            <i class="bi bi-bag-check me-2"></i> Pesanan Lunas
                        </a>
                    </nav>
                </div>
            </div>

            {{-- Main Content --}}
            <div class="col-lg-10 col-md-9 py-4 px-4">
                @yield('content')
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @yield('script-page')
</body>
</html>
