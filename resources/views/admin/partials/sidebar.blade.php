@php
    $user = auth()->user();
    $menu = [
        ['route' => 'admin.dashboard', 'label' => 'Dashboard', 'icon' => 'fa-dashboard', 'match' => 'admin.dashboard', 'perm' => null],
        ['route' => 'admin.orders.index', 'label' => 'Quản Lý Đơn hàng', 'icon' => 'fa-shopping-cart', 'match' => 'admin.orders.*', 'perm' => 'manage_orders'],
        ['route' => 'admin.products.index', 'label' => 'Quản Lý Sản phẩm', 'icon' => 'fa-cube', 'match' => 'admin.products.*', 'perm' => 'manage_products'],
        ['route' => 'admin.categories.index', 'label' => 'Quản Lý Danh mục', 'icon' => 'fa-list', 'match' => 'admin.categories.*', 'perm' => 'manage_categories'],
        ['route' => 'admin.users.index', 'label' => 'Quản Lý Người dùng', 'icon' => 'fa-users', 'match' => 'admin.users.*', 'perm' => 'manage_users'],
        ['route' => 'admin.roles.index', 'label' => 'Quản Lý Role & Quyền', 'icon' => 'fa-key', 'match' => 'admin.roles.*', 'perm' => 'manage_roles'],
        ['route' => 'admin.coupons.index', 'label' => 'Quản Lý Mã giảm giá', 'icon' => 'fa-ticket', 'match' => 'admin.coupons.*', 'perm' => 'manage_coupons'],
        ['route' => 'admin.reviews.index', 'label' => 'Quản Lý Đánh giá', 'icon' => 'fa-star', 'match' => 'admin.reviews.*', 'perm' => 'manage_reviews'],
        ['route' => 'admin.recipes.index', 'label' => 'Quản Lý Recipes', 'icon' => 'fa-cutlery', 'match' => 'admin.recipes.*', 'perm' => 'manage_recipes'],
       // ['route' => 'admin.contacts.index', 'label' => 'Liên hệ', 'icon' => 'fa-envelope', 'match' => 'admin.contacts.*', 'perm' => 'manage_contacts'],
    ];
@endphp

<aside class="admin-sidebar bg-dark text-white p-3 flex-shrink-0" id="adminSidebar">
    <div class="admin-sidebar-head mb-3">
        <div>
            <a href="{{ route('admin.dashboard') }}" class="text-white text-decoration-none fw-semibold">
                Xanh Organic
            </a>
            <div class="small text-white-50">Admin</div>
        </div>
        <button type="button" class="btn btn-link text-white admin-sidebar-close d-lg-none" id="adminSidebarClose" aria-label="Đóng menu">
            <i class="fa fa-times"></i>
        </button>
    </div>

    <nav class="nav flex-column gap-1">
        @foreach($menu as $item)
            @if($item['perm'] === null || $user->hasPermission($item['perm']))
                <a href="{{ route($item['route']) }}"
                   class="nav-link {{ request()->routeIs($item['match']) ? 'active' : '' }}">
                    <i class="fa {{ $item['icon'] }} me-1"></i> {{ $item['label'] }}
                </a>
            @endif
        @endforeach
    </nav>
</aside>
