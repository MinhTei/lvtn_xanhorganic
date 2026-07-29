<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — Xanh Organic</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="{{ asset('assets/admin/css/admin.css') }}" rel="stylesheet">
</head>
<body class="bg-light">
<div class="admin-overlay" id="adminSidebarOverlay" aria-hidden="true"></div>
<div class="d-flex admin-wrapper">
    @include('admin.partials.sidebar')

    <div class="admin-main">
        <nav class="navbar navbar-expand bg-white border-bottom px-3 py-2 admin-topbar">
            <button type="button" class="btn btn-link admin-sidebar-toggle d-lg-none" id="adminSidebarToggle" aria-label="Mở menu" aria-expanded="false" aria-controls="adminSidebar">
                <i class="fa fa-bars"></i>
            </button>
            <span class="navbar-brand mb-0 h6 text-truncate">@yield('title', 'Dashboard')</span>
            <div class="ms-auto d-flex align-items-center gap-2 admin-topbar-actions">
                <span class="small text-muted d-none d-sm-inline">
                    {{ auth()->user()->name }}
                </span>
                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary d-none d-md-inline-flex" target="_blank">Xem site</a>
                <form action="{{ route('admin.logout') }}" method="POST" class="m-0">
                    @csrf
                    <button type="submit" class="btn btn-sm btn-outline-danger">Đăng xuất</button>
                </form>
            </div>
        </nav>

        <main class="p-3">
            @include('admin.partials.alerts')
            @yield('content')
        </main>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('assets/admin/js/admin.js') }}"></script>
@stack('scripts')
</body>
</html>
