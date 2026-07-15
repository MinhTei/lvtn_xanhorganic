@extends('layouts.client')
@section('title', $product->name)
@section('breadcrumb', 'Chi tiết sản phẩm')
@section('content')

    <section class="product-details spad">
        <div class="container">
            <div class="row">

                {{-- Ảnh sản phẩm --}}
                <div class="col-lg-5 col-md-6">
                    <div class="product__details__pic">
                        <div class="product__details__pic__item">
                            <img src="{{ $product->image_url }}" alt="{{ $product->name }}" id="mainProductImage">
                        </div>

                        @if ($product->images->count() > 1)
                            <div class="product__details__pic__slider__placeholder mt-2">
                                @foreach ($product->images as $image)
                                    <div class="pt product-thumb" data-image="{{ $image->image_url }}">
                                        <img src="{{ $image->image_url }}" alt="{{ $product->name }}">
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Thông tin sản phẩm --}}
                <div class="col-lg-7 col-md-6">
                    <div class="product__details__text">
                        <h3>{{ $product->name }}</h3>

                        <div class="product__details__rating">
                            @for ($i = 1; $i <= 5; $i++)
                                <i class="fa fa-star{{ $i <= $fullStars ? '' : '-o' }}"></i>
                            @endfor
                            <span>({{ $reviewCount }} đánh giá)</span>
                        </div>

                        <div class="product__details__price">
                            {{ number_format($displayPrice, 0, ',', '.') }}₫
                            @if ($product->sale_price && $product->sale_price < $product->price)
                                <span class="original-price">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                            @endif
                        </div>

                        </p>

                        <div class="product__details__quantity">
                            <div class="quantity">
                                <div class="pro-qty">
                                    {{-- max = tồn kho; JS + server đều chặn vượt --}}
                                    <input type="number" id="quantity"
                                        value="{{ $product->quantity > 0 ? 1 : 0 }}"
                                        min="1"
                                        max="{{ max(0, $product->quantity) }}"
                                        {{ $product->quantity < 1 ? 'disabled' : '' }}>
                                </div>
                            </div>
                            @include('clients.partials.product_actions', ['product' => $product])
                        </div>

                        <ul>
                            <li><b>Tình trạng</b> <span>{{ $product->quantity > 0 ? 'Còn hàng' : 'Hết hàng' }}</span></li>
                            <li><b>Số lượng còn</b> <span>{{ $product->quantity }}</span></li>
                            <li><b>Đơn vị</b> <span>{{ $product->unit }}</span></li>
                            <li><b>Danh mục</b> <span>{{ $product->category->name ?? 'N/A' }}</span></li>
                        </ul>
                    </div>
                </div>

                {{-- Tab mô tả & đánh giá --}}
                <div class="col-lg-12">
                    <div class="product__details__tab">
                        <ul class="nav nav-tabs" role="tablist">
                            <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#tabs-1" role="tab">Mô
                                    tả chi tiết</a></li>
                            <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#tabs-2" role="tab">Đánh giá
                                    ({{ $reviewCount }})</a></li>
                        </ul>
                        <div class="tab-content">
                            <div class="tab-pane active" id="tabs-1" role="tabpanel">
                                <div class="product__details__tab__desc">
                                    <h6>Thông tin sản phẩm</h6>
                                    @if ($product->description)
                                        {!! nl2br(e($product->description)) !!}
                                    @else
                                        <p>Chưa có mô tả chi tiết cho sản phẩm này.</p>
                                    @endif
                                </div>
                            </div>
                            <div class="tab-pane" id="tabs-2" role="tabpanel">
                                <div class="product__details__tab__desc">
                                    <h4 class="review-title">Khách hàng nhận xét ({{ $reviewCount }})</h4>
                                    @forelse ($reviews as $review)
                                        <div class="review-item">
                                            <div class="review-item__header">
                                                <div class="review-avatar">
                                                    {{ strtoupper(substr($review->user->name ?? 'K', 0, 1)) }}</div>
                                                <div>
                                                    <strong>{{ $review->user->name ?? 'Khách hàng' }}</strong>
                                                    <span
                                                        class="review-date">{{ $review->created_at->format('d/m/Y H:i') }}</span>
                                                </div>
                                            </div>
                                            <div class="review-stars">
                                                @for ($i = 1; $i <= 5; $i++)<i
                                                class="fa fa-star{{ $i <= $review->rating ? '' : '-o' }}"></i>@endfor
                                            </div>
                                            <p class="review-comment">{{ $review->comment }}</p>
                                        </div>
                                    @empty
                                        <div class="review-empty text-center">
                                            <i class="fa fa-comments"></i>
                                            <p>Sản phẩm chưa có đánh giá nào.</p>
                                        </div>
                                    @endforelse
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    {{-- Sản phẩm liên quan --}}
    @if ($relatedProducts->count() > 0)
    <section class="related-product">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="section-title related__product__title"><h2>Sản phẩm tương tự</h2></div>
                </div>
            </div>
            <div class="row">
                @include('clients.partials.products_grid', ['products' => $relatedProducts])
            </div>
        </div>
    </section>
    @endif

@endsection