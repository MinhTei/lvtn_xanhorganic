@extends('layouts.client')
@section('title', 'XanhOrganic')
@section('content')
    <section class="hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-3">
                    <div class="hero__categories">
                        <div class="hero__categories__all">
                            <i class="fa fa-bars"></i>
                            <span>Danh Mục</span>
                        </div>
                        <ul>
                            @foreach ($categories as $category)
                                <li>
                                    @if($category->children->isNotEmpty())
                                        {{-- Nút bấm xổ xuống --}}
                                        <a href="#cat-{{$category->id}}" data-toggle="collapse">
                                            {{$category->name}} <i class="fa fa-angle-down"></i>
                                        </a>
                                        {{-- Danh sách con bị ẩn đi--}}
                                        <ul class="collapse" id="cat-{{$category->id}}"
                                            style="border: none; padding: 0 0 0 30px; margin: 0;">
                                            @foreach($category->children as $child)
                                                <li><a href="{{route('products', ['category_id' => $child->id])}}">{{$child->name}}</a></li>
                                            @endforeach
                                        </ul>
                                    @else
                                        <a href="{{route('products', ['category_id' => $category->id])}}">{{$category->name}}</a>
                                    @endif
                                </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="hero__slider owl-carousel">
                        <div class="hero__item set-bg" data-setbg="{{asset('assets/clients/img/hero/banner2.jpg')}}">
                            <div class="hero__text">
                                <a href="{{route('products')}}" class="primary-btn">MUA NGAY</a>
                            </div>
                        </div>
                        <div class="hero__item set-bg" data-setbg="{{asset('assets/clients/img/hero/banner1.jpg')}}">
                            <div class="hero__text">
                                <a href="{{route('products')}}" class="primary-btn">MUA NGAY</a>
                            </div>
                        </div>
                        <div class="hero__item set-bg" data-setbg="{{asset('assets/clients/img/hero/banner4.jpg')}}">
                            <div class="hero__text">
                                <a href="{{route('products')}}" class="primary-btn">MUA NGAY</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Hero Section End -->

    <!-- Categories Section Begin -->
    {{-- <section class="categories">
        <div class="container">
            <div class="row">
                <div class="categories__slider owl-carousel">
                    @foreach($categories as $category)
                    <div class="col-lg-3">
                        <div class="categories__item set-bg" data-setbg="img/categories/cat-1.jpg">
                            <h5><a href="#">{{$category->name}}</a></h5>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section> --}}

    <section class="featured spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title">
                        <h2>Sản Phẩm</h2>
                    </div>
                </div>
            </div>
            <div id="product-list-area">
                <div class="row featured__filter">
                    @include('clients.partials.products_grid', ['products' => $products])
                </div>
                <div class="d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </section>
    <!-- Featured Section End -->

    <!-- Banner Begin -->
    <div class="banner">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="banner__pic">
                        <a href="{{route('recipes')}}"><img src="{{asset('assets/clients/img/banner/banner-1.jpg')}}"
                                alt=""></a>
                        <!-- <a href="{{route('promos')}}" class="primary-btn">MUA NGAY</a> -->
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6">
                    <div class="banner__pic">
                        <a href="{{route('promos')}}"><img src="{{asset('assets/clients/img/banner/banner-2.jpg')}}"
                                alt=""></a>
                        <!-- <a href="{{route('promos')}}" class="primary-btn" style="display: flex;padding-left: 250px">MUA NGAY</a> -->
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Banner End -->

    <!-- Latest Product Section Begin -->
    <section class="latest-product spad">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 col-md-6">
                    <div class="latest-product__text">
                        <h4>Sản phẩm nổi bật</h4>
                        <div class="latest-product__slider owl-carousel">
                            @foreach($featuredProducts->chunk(3) as $chunk)
                                <div class="latest-product__slider__item">
                                    @foreach($chunk as $product)
                                        <div class="latest-product__item">
                                            <div class="latest-product__item__pic position-relative">
                                                <a href="{{route('product.detail', $product->slug)}}">
                                                    <img src="{{ $product->image_url ?? asset('assets/clients/img/product/' . ($product->slug ?? '') . '.jpg') }}"
                                                        alt="{{ $product->name }}">
                                                </a>
                                                @if($product->getLabelPriceSale() > 0)
                                                    <span class="label-sale">-{{ $product->getLabelPriceSale() }}%</span>
                                                @endif
                                            </div>
                                            <div class="latest-product__item__text">
                                                <a href="{{route('product.detail', $product->slug)}}">
                                                    <h6>{{$product->name}}</h6>
                                                </a>
                                                <span>{{ number_format($product->sale_price ?? $product->price, 0, ',', '.') }}
                                                    VNĐ</span>
                                                <!-- <div class="d-flex justify-content-between">
                                                    @include('clients.partials.product_actions', ['product' => $product])
                                                </div> -->
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 latest-product-divider">
                    <div class="latest-product__text">
                        <h4>Sản phẩm mới</h4>
                        <div class="latest-product__slider owl-carousel">
                            @foreach($latestProducts->chunk(3) as $chunk)
                                <div class="latest-product__slider__item">
                                    @foreach($chunk as $product)
                                        <div class="latest-product__item">
                                            <div class="latest-product__item__pic position-relative">
                                                <a href="{{route('product.detail', $product->slug)}}">
                                                    <img src="{{ $product->image_url ?? asset('assets/clients/img/product/' . ($product->slug ?? '') . '.jpg') }}"
                                                        alt="{{$product->name}}">
                                                </a>
                                                @if($product->getLabelPriceSale() > 0)
                                                    <span class="label-sale">-{{ $product->getLabelPriceSale() }}%</span>
                                                @endif
                                            </div>
                                            <div class="latest-product__item__text">
                                                <a href="{{route('product.detail', $product->slug)}}">
                                                    <h6>{{$product->name}}</h6>
                                                </a>
                                                <span>{{ number_format($product->sale_price ?? $product->price, 0, ',', '.') }}
                                                    VNĐ</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Latest Product Section End -->
@endsection