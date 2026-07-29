@extends('layouts.client')

@section('title', 'Giỏ hàng')
@section('breadcrumb', 'Giỏ hàng')

@section('content')
<section class="cart spad" id="cart-section">
    <div class="container">
        @if($cartItems->isEmpty())
            <div class="cart-empty text-center">
                <i class="fa fa-shopping-cart"></i>
                <h2>Giỏ hàng trống</h2>
                <p>Hãy thêm sản phẩm vào giỏ hàng để tiếp tục mua sắm.</p>
                <a href="{{ route('products') }}" class="site-btn mt-3">KHÁM PHÁ SẢN PHẨM</a>
            </div>
        @else
            <div class="row">
                <div class="col-lg-8">
                    @foreach($cartItems as $item)
                        @php
                            $product = $item->product;
                            $price = $product->sale_price ?? $product->price;
                        @endphp
                        <div class="cart-item">
                            <div class="thumb">
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                            </div>
                            <div class="info">
                                <h5><a href="{{ route('product.detail', $product->slug) }}">{{ $product->name }}</a></h5>
                                <p class="price">{{ number_format($price, 0, ',', '.') }}₫ / {{ $product->unit ?? 'sp' }}</p>
                                <form action="{{ route('cart.update', $product->id) }}" method="POST" class="cart-update-form m-0">
                                    @csrf
                                    @method('PUT')
                                    <div class="qty mt-2">
                                        <span class="qty-btn-small dec">-</span>
                                        <input type="number"
                                               class="cart-qty-input"
                                               name="quantity"
                                               value="{{ min($item->quantity, max(1, $product->quantity)) }}"
                                               min="1"
                                               max="{{ max(0, $product->quantity) }}"
                                               data-old-qty="{{ $item->quantity }}"
                                               data-stock="{{ $product->quantity }}"
                                               {{ $product->quantity < 1 ? 'disabled' : '' }}>
                                        <span class="qty-btn-small inc">+</span>
                                    </div>
                                    @if($product->quantity < 1)
                                        <small class="text-danger d-block mt-1">Hết hàng — vui lòng xóa khỏi giỏ</small>
                                    @else
                                        <small class="text-muted d-block mt-1">Còn {{ $product->quantity }} trong kho</small>
                                    @endif
                                </form>
                            </div>
                            <div class="action">
                                <form action="{{ route('cart.destroy', $product->id) }}" method="POST" class="cart-delete-form m-0">
                                    @csrf
                                    @method('DELETE')
                                    <button type="button" class="btn-delete-cart" title="Xóa">
                                        <i class="fa fa-trash-o fa-lg"></i>
                                    </button>
                                </form>
                                <div class="total">
                                    <span>Thành tiền</span>
                                    <strong>{{ number_format($price * $item->quantity, 0, ',', '.') }}₫</strong>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <div class="col-lg-4">
                    @php $shippingFee = $subtotal >= 500000 ? 0 : 25000; @endphp
                    <div class="cart-summary">
                        <h5>Tóm tắt đơn hàng</h5>
                        <ul>
                            <li>Tạm tính <span>{{ number_format($subtotal, 0, ',', '.') }}₫</span></li>
                            <li>Phí vận chuyển <span>{{ $shippingFee == 0 ? 'Miễn phí' : number_format($shippingFee, 0, ',', '.') . '₫' }}</span></li>
                        </ul>

                        @if($shippingFee > 0)
                            <div class="ship-note">
                                Mua thêm <strong>{{ number_format(500000 - $subtotal, 0, ',', '.') }}₫</strong> để được miễn phí vận chuyển!
                            </div>
                        @endif

                        <div class="grand-total">
                            Tổng cộng <span>{{ number_format($subtotal + $shippingFee, 0, ',', '.') }}₫</span>
                        </div>

                        <a href="{{ route('checkout') }}" class="site-btn d-block text-center">THANH TOÁN</a>
                        @guest
                            <p class="guest-note text-center text-muted mt-2 mb-0">
                                Bạn cần đăng nhập để hoàn tất thanh toán.
                            </p>
                        @endguest
                        <div class="text-center mt-3">
                            <a href="{{ route('products') }}" class="back-shop">
                                <i class="fa fa-long-arrow-left"></i> Tiếp tục mua sắm
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
@endsection
