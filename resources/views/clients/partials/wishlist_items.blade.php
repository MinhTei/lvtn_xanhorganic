@if($wishlistItems->isEmpty())
    <div class="text-center py-4 text-muted">
        <i class="fa fa-heart-o fa-2x mb-2"></i>
        <p class="mb-0">Danh sách yêu thích trống.</p>
    </div>
@else
    <div class="wishlist-items-list">
        @foreach($wishlistItems as $wishlistItem)
            @php $product = $wishlistItem->product; @endphp
            @if(!$product)
                @continue
            @endif
            <div class="d-flex align-items-center justify-content-between border-bottom py-3">
                <div class="d-flex align-items-center" style="gap: 12px;">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                         style="width: 56px; height: 56px; object-fit: cover; border-radius: 6px;">
                    <div>
                        <a href="{{ route('product.detail', $product->slug) }}" class="font-weight-bold text-dark">
                            {{ $product->name }}
                        </a>
                        <div class="text-muted" style="font-size: 13px;">
                            {{ number_format($product->sale_price ?? $product->price, 0, ',', '.') }}₫
                        </div>
                    </div>
                </div>
                <button type="button"
                        class="btn btn-sm btn-outline-danger js-remove-wishlist"
                        data-wishlist-id="{{ $wishlistItem->id }}"
                        data-product-id="{{ $product->id }}"
                        title="Xóa">
                    <i class="fa fa-trash-o"></i>
                </button>
            </div>
        @endforeach
    </div>
@endif
