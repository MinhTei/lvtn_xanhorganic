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
<div class="d-flex flex-column flex-md-row">
    @include('admin.partials.sidebar')

    <div class="admin-main">
        <nav class="navbar navbar-expand bg-white border-bottom px-3 py-2">
            <span class="navbar-brand mb-0 h6">@yield('title', 'Dashboard')</span>
            <div class="ms-auto d-flex align-items-center gap-2">
                <span class="small text-muted">
                    {{ auth()->user()->name }}
                    <span class="badge text-bg-secondary">{{ auth()->user()->role->name ?? '' }}</span>
                </span>
                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary" target="_blank">Xem site</a>
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
@stack('scripts')
</body>
</html>
