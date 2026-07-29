@forelse ($products as $product)
    <div class="col-6 col-md-6 col-lg-4 mb-4">
        <div class="product-card">
            <div class="thumb">
                <a href="{{ route('product.detail', $product->slug) }}">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}">
                </a>
            </div>
            <div class="body">
                <h6>
                    <a href="{{ route('product.detail', $product->slug) }}">{{ $product->name }}</a>
                </h6>
                <div class="price">
                    {{ number_format($product->sale_price ?? $product->price, 0, ',', '.') }}₫
                    @if ($product->sale_price && $product->sale_price < $product->price)
                        <span class="old">{{ number_format($product->price, 0, ',', '.') }}₫</span>
                    @endif
                </div>
                @include('clients.partials.product_actions', ['product' => $product])
            </div>
        </div>
    </div>
@empty
    <div class="col-12 text-center py-5">
        <p>Không tìm thấy sản phẩm nào phù hợp với bộ lọc.</p>
        <a href="{{ route('products') }}" class="site-btn mt-3">Xem tất cả sản phẩm</a>
    </div>
@endforelse
