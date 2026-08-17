{{-- Mobile side menu --}}
<div class="humberger__menu__overlay"></div>
<div class="humberger__menu__wrapper">
    <div class="humberger__menu__logo">
        <a href="{{ route('home') }}">
            <img src="{{ asset('assets/clients/img/xanhorganic.png') }}" alt="Xanh Organic">
        </a>
    </div>

    <div class="humberger__menu__cart">
        <ul>
            <li>
                <a href="javascript:void(0)" id="btn-open-wishlist-mobile">
                    <i class="fa fa-heart"></i>
                    <span class="wishlist-count">{{ \App\Services\ClientWishlist::count() }}</span>
                </a>
            </li>
            <li>
                <a href="{{ route('cart') }}">
                    <i class="fa fa-shopping-bag"></i>
                    <span class="cart-count">{{ \App\Services\ClientCart::count() }}</span>
                </a>
            </li>
        </ul>
    </div>

    <div class="humberger__menu__widget">
        <div class="header__top__right__auth">
            @if(auth()->check())
                <a href="{{ route('account') }}"><i class="fa fa-user"></i> Tài khoản</a>
            @else
                <a href="{{ route('login') }}"><i class="fa fa-user"></i> Đăng nhập</a>
            @endif
        </div>
    </div>

    {{-- Menu tự render (không dùng slicknav) để chèn Danh mục đúng chỗ --}}
    <nav class="mobile-nav">
        <ul>
            <li class="{{ request()->routeIs('home') ? 'active' : '' }}">
                <a href="{{ route('home') }}">Trang chủ</a>
            </li>

            @if(isset($navCategories) && $navCategories->isNotEmpty())
                <li class="mobile-nav-cat">
                    <a href="#mobile-cat-panel" class="mobile-cat-toggle collapsed" data-toggle="collapse" aria-expanded="false">
                        Danh mục <i class="fa fa-angle-down"></i>
                    </a>
                    <div class="collapse" id="mobile-cat-panel">
                        <ul class="mobile-cat-list">
                            @foreach($navCategories as $category)
                                <li>
                                    @if($category->children->isNotEmpty())
                                        <a href="#m-cat-{{ $category->id }}" class="mobile-cat-parent collapsed" data-toggle="collapse" aria-expanded="false">
                                            {{ $category->name }} <i class="fa fa-angle-down"></i>
                                        </a>
                                        <ul class="collapse mobile-cat-children" id="m-cat-{{ $category->id }}">
                                            @foreach($category->children as $child)
                                                <li>
                                                    <a href="{{ route('products', ['category_id' => $child->id]) }}">{{ $child->name }}</a>
                                                </li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <a href="{{ route('products', ['category_id' => $category->id]) }}">{{ $category->name }}</a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </li>
            @endif

            <li class="{{ request()->routeIs('products') ? 'active' : '' }}">
                <a href="{{ route('products') }}">Sản phẩm</a>
            </li>
            <li class="{{ request()->routeIs('about') ? 'active' : '' }}">
                <a href="{{ route('about') }}">Về chúng tôi</a>
            </li>
            <li class="{{ request()->routeIs('contact') ? 'active' : '' }}">
                <a href="{{ route('contact') }}">Liên hệ</a>
            </li>
        </ul>
    </nav>
</div>
