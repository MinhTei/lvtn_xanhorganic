@extends('layouts.client')

@section('title', 'Giỏ hàng')
@section('breadcrumb', 'Giỏ hàng')

@section('content')

<!-- Shoping Cart Section Begin -->
<section class="shoping-cart spad" id="cart-section">
    <div class="container">
        @if($cartItems->isEmpty())
            <div class="empty-cart-container text-center">
                <span class="material-symbols-outlined empty-cart-icon">shopping_cart</span>
                <h2>Giỏ hàng trống</h2>
                <p>Hãy thêm sản phẩm vào giỏ hàng để tiếp tục mua sắm.</p>
                <a href="{{ route('products') }}" class="site-btn rounded-btn mt-3">KHÁM PHÁ SẢN PHẨM</a>
            </div>
        @else
            <div class="row">
                <div class="col-lg-8">
                    <div class="cart-items-container">
                        @foreach($cartItems as $item)
                            @php
                                $product = $item->product;
                                $price = $product->sale_price ?? $product->price;
                            @endphp
                            <div class="cart-item-card">
                                <div class="cart-item-img">
                                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                                </div>
                                <div class="cart-item-info">
                                    <h5><a href="{{ route('product.detail', $product->slug) }}">{{ $product->name }}</a></h5>
                                    <div class="cart-item-price">{{ number_format($price, 0, ',', '.') }}₫ / {{ $product->unit ?? 'sp' }}</div>
                                    
                                    <form action="{{ route('cart.update', $product->id) }}" method="POST" class="ajax-cart-update-form m-0">
                                        @csrf @method('PUT')
                                        <div class="pro-qty-small mt-2">
                                            <span class="qtybtn-small dec">-</span>
                                            {{-- max = tồn kho hiện có; vượt sẽ bị server/JS chặn --}}
                                            <input type="number"
                                                   class="ajax-cart-qty-input"
                                                   name="quantity"
                                                   value="{{ min($item->quantity, max(1, $product->quantity)) }}"
                                                   min="1"
                                                   max="{{ max(0, $product->quantity) }}"
                                                   data-old-qty="{{ $item->quantity }}"
                                                   data-stock="{{ $product->quantity }}"
                                                   {{ $product->quantity < 1 ? 'disabled' : '' }}>
                                            <span class="qtybtn-small inc">+</span>
                                        </div>
                                        @if($product->quantity < 1)
                                            <small class="text-danger d-block mt-1">Hết hàng — vui lòng xóa khỏi giỏ</small>
                                        @else
                                            <small class="text-muted d-block mt-1">Còn {{ $product->quantity }} trong kho</small>
                                        @endif
                                    </form>
                                </div>
                                
                                <div class="cart-item-action text-right">
                                    <form action="{{ route('cart.destroy', $product->id) }}" method="POST" class="ajax-cart-delete-form m-0">
                                        @csrf @method('DELETE')
                                        <button type="button" class="border-0 bg-transparent p-0 btn-delete-cart text-danger mb-2" title="Xóa">
                                            <i class="fa fa-trash-o fa-lg"></i>
                                        </button>
                                    </form>
                                    <div class="cart-item-total">
                                        <span class="d-block text-muted" style="font-size: 13px;">Thành tiền</span>
                                        <strong>{{ number_format($price * $item->quantity, 0, ',', '.') }}₫</strong>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="cart-summary-card">
                        <h5>Tóm tắt đơn hàng</h5>
                        @php
                            $shippingFee = $subtotal >= 500000 ? 0 : 25000;
                        @endphp
                        <ul class="summary-list">
                            <li>Tạm tính <span>{{ number_format($subtotal, 0, ',', '.') }}₫</span></li>
                            <li>Phí vận chuyển <span>{{ $shippingFee == 0 ? 'Miễn phí' : number_format($shippingFee, 0, ',', '.') . '₫' }}</span></li>
                        </ul>
                        
                        @if($shippingFee > 0)
                        <div class="shipping-alert">
                            Mua thêm <strong>{{ number_format(500000 - $subtotal, 0, ',', '.') }}₫</strong> để được miễn phí vận chuyển!
                        </div>
                        @endif
                        
                        <div class="summary-total">
                            Tổng cộng <span>{{ number_format($subtotal + $shippingFee, 0, ',', '.') }}₫</span>
                        </div>
                        {{-- Guest bấm vào sẽ bị auth chuyển sang login, rồi intended quay lại checkout --}}
                        <a href="{{ route('checkout') }}" class="site-btn rounded-btn d-block text-center mt-3">THANH TOÁN</a>
                        @guest
                            <p class="text-center text-muted mt-2 mb-0" style="font-size: 13px;">
                                Bạn cần đăng nhập để hoàn tất thanh toán.
                            </p>
                        @endguest
                        <div class="text-center mt-3">
                            <a href="{{ route('products') }}" class="continue-shopping text-secondary"><i class="fa fa-long-arrow-left"></i> Tiếp tục mua sắm</a>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</section>
<!-- Shoping Cart Section End -->

@endsection
