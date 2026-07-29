<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Xanh Organic">
    <meta name="keywords" content="Xanh Organic,unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title')</title>

    <!-- Google Font -->
    <link
        href="https://fonts.googleapis.com/css2?family=Be+Vietnam+Pro:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
    <!-- Css Styles -->
    <link rel="stylesheet" href="{{asset('assets/clients/css/bootstrap.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('assets/clients/css/font-awesome.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('assets/clients/css/elegant-icons.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('assets/clients/css/nice-select.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('assets/clients/css/jquery-ui.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('assets/clients/css/owl.carousel.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('assets/clients/css/slicknav.min.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('assets/clients/css/style.css')}}?v={{ time() }}" type="text/css">
    <link rel="stylesheet" href="{{asset('assets/clients/css/custom.css')}}" type="text/css">
    <link rel="stylesheet" href="{{asset('assets/clients/css/toastr.min.css')}}" type="text/css">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    @php
        $cartCount = \App\Services\ClientCart::count();
        $wishlistCount = \App\Services\ClientWishlist::count();
    @endphp

    <script>
        window.XanhOrganic = {
            isLoggedIn: {{ auth()->check() ? 'true' : 'false' }},
            cartAddUrl: '{{ route("cart.store") }}',
            wishlistUrl: '{{ route("wishlists") }}',
            wishlistAddUrl: '{{ route("wishlist.store") }}',
            wishlistRemoveUrl: '{{ url("/wishlists") }}',
            wishlistAddAllUrl: '{{ route("wishlist.addAllToCart") }}',
            couponApplyUrl: '{{ auth()->check() ? route("checkout.coupon") : "" }}',
            cartCount: {{ $cartCount }},
            wishlistCount: {{ $wishlistCount }},
            loginUrl: '{{ route("login") }}'
        };
    </script>
</head>

<body>
    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

    @include('clients.partials.mobile_hum')
    @include('clients.partials.header')
    
    @hasSection('breadcrumb')
        @include('clients.partials.breadcrumb')
    @endif

    <main>
        @yield('content')
    </main>
    @include('clients.components.includes.include_modal')
    @include('clients.partials.footer')


    <!-- Back to Top Button -->
    <button id="backToTopBtn" title="Lên đầu trang"><i class="fa fa-angle-up"></i></button>

    <!-- Js Plugins -->
    <script src="{{asset('assets/clients/js/jquery-3.3.1.min.js')}}"></script>
    <script src="{{asset('assets/clients/js/bootstrap.min.js')}}"></script>
    <script src="{{asset('assets/clients/js/jquery.nice-select.min.js')}}"></script>
    <script src="{{asset('assets/clients/js/jquery-ui.min.js')}}"></script>
    <script src="{{asset('assets/clients/js/jquery.slicknav.js')}}"></script>
    <script src="{{asset('assets/clients/js/mixitup.min.js')}}"></script>
    <script src="{{asset('assets/clients/js/owl.carousel.min.js')}}"></script>
    <script src="{{asset('assets/clients/js/main.js')}}"></script>
    <script src="{{asset('assets/clients/js/auth.js')}}"></script>
    <script src="{{asset('assets/clients/js/cart.js')}}"></script>
    <script src="{{asset('assets/clients/js/hcm-address-data.js')}}"></script>
    <script src="{{asset('assets/clients/js/address-location.js')}}"></script>
    <script src="{{asset('assets/clients/js/account.js')}}"></script>
    <script src="{{asset('assets/clients/js/checkout.js')}}"></script>
    <script src="{{asset('assets/clients/js/contact.js')}}"></script>
    <script src="{{asset('assets/clients/js/product_detail.js')}}"></script>
    <script src="{{asset('assets/clients/js/product-actions.js')}}"></script>
    <script src="{{asset('assets/clients/js/header_action.js')}}"></script>
    <script src="{{asset('assets/clients/js/products.js')}}?v={{ time() }}"></script>
    <script src="{{asset('assets/clients/js/toastr.min.js')}}"></script>
    @include('clients.partials.toastr')
    @stack('scripts')

</body>

</html>