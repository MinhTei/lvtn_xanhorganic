    <!-- Header Humberger Mobile Begin -->
    <div class="humberger__menu__overlay"></div>
    <div class="humberger__menu__wrapper">
        <div class="humberger__menu__logo">
            <a href="{{ route('home') }}"><img src="{{ asset('assets/clients/img/xanhorganic.png') }}" alt="Xanh Organic" style="max-width: 150px;"></a>
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
        <nav class="humberger__menu__nav mobile-menu">
            <ul>
                <li class="{{ request()->routeIs('home') ? 'active' : '' }}"><a href="{{ route('home') }}">Trang chủ</a></li>
                <li class="{{ request()->routeIs('products') ? 'active' : '' }}"><a href="{{ route('products') }}">Sản phẩm</a></li>
                <li class="{{ request()->routeIs('about') ? 'active' : '' }}"><a href="{{ route('about') }}">Về chúng tôi</a></li>
                <li class="{{ request()->routeIs('contact') ? 'active' : '' }}"><a href="{{ route('contact') }}">Liên hệ</a></li>
            </ul>
        </nav>
        <div id="mobile-menu-wrap"></div>
    </div>
    <!-- Header Humberger Mobile End -->
