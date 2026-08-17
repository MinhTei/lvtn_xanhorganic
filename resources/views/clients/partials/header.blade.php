<!-- Header Section Begin -->
<header class="header">
    <div class="container">
        <div class="row align-items-center header-row">
            <div class="col-auto header-left d-lg-none">
                <div class="humberger__open">
                    <i class="fa fa-bars"></i>
                </div>
            </div>

            <div class="col-lg-3 col header-brand">
                <div class="header__logo">
                    <div class="header__logo__img">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('assets/clients/img/xanhorganic.png') }}" alt="Xanh Organic">
                        </a>
                    </div>
                </div>
            </div>

            <div class="col-lg-6 d-none d-lg-block">
                <nav class="header__menu">
                    <ul>
                        <li class="{{ request()->routeIs('home') ? 'active' : '' }}">
                            <a href="{{ route('home') }}">Trang chủ</a>
                        </li>
                        <li class="{{ request()->routeIs('products') ? 'active' : '' }}">
                            <a href="{{ route('products') }}">Sản phẩm</a>
                        </li>
                         <li class="{{ request()->routeIs('recipes') ? 'active' : '' }}">
                            <a href="{{ route('recipes') }}">Góc ẩm thực</a>
                        </li>
                         <li class="{{ request()->routeIs('promos') ? 'active' : '' }}">
                            <a href="{{ route('promos') }}">Khuyến mãi</a>
                        </li>
                        <li class="{{ request()->routeIs('about') ? 'active' : '' }}">
                            <a href="{{ route('about') }}">Về chúng tôi</a>
                        </li>
                        <!-- <li class="{{ request()->routeIs('contact') ? 'active' : '' }}">
                            <a href="{{ route('contact') }}">Liên hệ</a>
                        </li> -->
                    </ul>
                </nav>
                <div class="hero__search">
                    <div class="hero__search__form">
                        <form action="{{ route('products') }}" method="GET">
                            <input type="text" name="q" value="{{ request('q') }}" placeholder="Tìm kiếm sản phẩm..."
                                autocomplete="off">
                            <button type="submit" class="site-btn">Tìm kiếm</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-3 col-auto header-actions ml-auto">
                <div class="header__cart">
                    <ul>
                        <li class="header__profile__dropdown">
                            @if(Auth::check())
                                <a href="{{Auth::user()->canAccessAdmin()}}"><i class="fa fa-user"></i></a>
                                <ul class="profile__menu">
                                    @if(Auth::user()->canAccessAdmin())
                                        <li><a href="{{ route('admin.dashboard') }}">Trang quản trị</a></li>
                                        <li><a href="{{ route('account') }}">Tài khoản</a></li>
                                    @else
                                        <li><a href="{{ route('account') }}">Tài khoản</a></li>
                                    @endif
                                    <li>
                                        <a href="javascript:void(0)" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Đăng xuất</a>
                                        <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                            @csrf
                                        </form>
                                    </li>
                                </ul>
                            @else
                                <a href="{{ route('login') }}"><i class="fa fa-user"></i></a>
                                <ul class="profile__menu">
                                    <li><a href="{{ route('login') }}">Đăng nhập</a></li>
                                    <li><a href="{{ route('register') }}">Đăng ký</a></li>
                                </ul>
                            @endif
                        </li>
                        <li class="header__profile__dropdown">
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#wishlistModal"
                                id="btn-open-wishlist">
                                <i class="fa fa-heart"></i>
                                @php $wishlistCount = \App\Services\ClientWishlist::count(); @endphp
                                <span id="wishlist-badge" class="wishlist-count"
                                    style="display: {{ $wishlistCount > 0 ? 'inline-block' : 'none' }}">{{ $wishlistCount }}</span>
                            </a>
                        </li>
                        <li class="header__profile__dropdown">
                            <a href="{{ route('cart') }}">
                                <i class="fa fa-shopping-bag"></i>
                                @php $cartCount = \App\Services\ClientCart::count(); @endphp
                                <span id="cart-badge" class="cart-count"
                                    style="display: {{ $cartCount > 0 ? 'inline-block' : 'none' }}">{{ $cartCount }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        {{-- Search bar riêng cho mobile/tablet --}}
        <div class="header-mobile-search d-lg-none">
            <form action="{{ route('products') }}" method="GET">
                <input type="text" name="q" value="{{ request('q') }}" placeholder="Tìm kiếm sản phẩm..."
                    autocomplete="off">
                <button type="submit" class="site-btn">Tìm</button>
            </form>
        </div>
    </div>
</header>
<!-- Header Section End -->