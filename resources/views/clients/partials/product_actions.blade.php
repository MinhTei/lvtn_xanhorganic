{{--
    Partial: product_actions
    Biến nhận vào: $product (App\Models\Product)
    data-stock: tồn kho để JS chặn thêm khi hết hàng / vượt số lượng
--}}
<div class="product-actions">
    <a href="javascript:void(0)"
       class="btn-heart js-add-wishlist"
       data-product-id="{{ $product->id }}"
       title="Thêm vào yêu thích">
        <i class="fa fa-heart"></i>
    </a>

    @if($product->quantity > 0)
        <a href="javascript:void(0)"
           class="btn-add-cart js-add-cart"
           data-product-id="{{ $product->id }}"
           data-stock="{{ $product->quantity }}"
           title="Thêm vào giỏ hàng">
            <i class="fa fa-shopping-cart"></i> Thêm giỏ hàng
        </a>
    @else
        <a href="javascript:void(0)"
           class="btn-add-cart disabled"
           data-stock="0"
           title="Hết hàng"
           style="opacity: 0.55; pointer-events: none; cursor: not-allowed;">
            <i class="fa fa-ban"></i> Hết hàng
        </a>
    @endif
</div>
