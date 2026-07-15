<!-- Header Section Begin -->
<header class="header">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="header__logo">
                    <div class="header__logo__img">
                        <a href="{{route('home')}}"><img src="{{asset('assets/clients/img/xanhorganic.png')}}"
                                alt=""></a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <nav class="header__menu">
                    <ul>
                        <li class="{{ request()->routeIs('home') ? 'active' : '' }}"><a href="{{route('home')}}">Trang
                                chủ </a></li>
                        <li class="{{ request()->routeIs('products') ? 'active' : '' }}"><a
                                href="{{route('products')}}">Sản phẩm</a></li>
                        <li class="{{ request()->routeIs('about') ? 'active' : '' }}"><a href="{{route('about')}}">Về
                                chúng tôi</a></li>
                        <li class="{{ request()->routeIs('contact') ? 'active' : '' }}"><a
                                href="{{route('contact')}}">Liên hệ</a></li>
                    </ul>
                </nav>
                <div class="hero__search">
                    <div class="hero__search__form">
                        <form action="{{ route('products') }}" method="GET">
                            <input type="text" name="q" value="{{ request('q') }}"
                                   placeholder="Tìm kiếm sản phẩm..." autocomplete="off">
                            <button type="submit" class="site-btn">Tìm kiếm</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="header__cart">
                    <ul>
                        <li class="header__profile__dropdown">
                            @if(Auth::check())
                                <a href="{{ Auth::user()->canAccessAdmin() ? route('admin.dashboard') : route('account') }}"><i class="fa fa-user"></i></a>
                                <ul class="profile__menu">
                                    @if(Auth::user()->canAccessAdmin())
                                        <li><a href="{{ route('admin.dashboard') }}">Trang quản trị</a></li>
                                    @else
                                        <li><a href="{{ route('account') }}">Tài khoản</a></li>
                                    @endif
                                    <li>
                                        <form action="{{ route('logout') }}" method="POST" class="m-0">
                                            @csrf
                                            <button type="submit" class="border-0 bg-transparent p-0 w-100 text-left" style="padding: inherit; color: inherit; cursor: pointer;">
                                                Đăng xuất
                                            </button>
                                        </form>
                                    </li>
                                </ul>
                            @else
                                <a href="{{route('login') }}"><i class="fa fa-user"></i></a>
                                <ul class="profile__menu">
                                    <li><a href="{{route('login') }}">Đăng nhập</a></li>
                                    <li><a href="{{route('register') }}">Đăng ký</a></li>
                                </ul>
                            @endif
                        </li>
                        <li class="header__profile__dropdown">
                            <a href="javascript:void(0)" data-toggle="modal" data-target="#wishlistModal" id="btn-open-wishlist">
                                <i class="fa fa-heart"></i>
                                @php $wishlistCount = \App\Services\ClientWishlist::count(); @endphp
                                <span id="wishlist-badge" class="wishlist-count" style="display: {{ $wishlistCount > 0 ? 'inline-block' : 'none' }}">{{ $wishlistCount }}</span>
                            </a>
                        </li>
                        <li class="header__profile__dropdown">
                            <a href="{{ route('cart') }}">
                                <i class="fa fa-shopping-bag"></i>
                                @php $cartCount = \App\Services\ClientCart::count(); @endphp
                                <span id="cart-badge" class="cart-count" style="display: {{ $cartCount > 0 ? 'inline-block' : 'none' }}">{{ $cartCount }}</span>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="humberger__open">
            <i class="fa fa-bars"></i>
        </div>
    </div>
</header>
<!-- Header Section End -->