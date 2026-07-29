@php $inWishlist = \App\Services\ClientWishlist::constains($product->id); @endphp
<div class="product-actions">
    <a href="javascript:void(0)"
       class="btn-heart js-add-wishlist {{ $inWishlist ? 'is-active' : '' }}"
       data-product-id="{{ $product->id }}"
       title="{{ $inWishlist ? 'Đã yêu thích' : 'Thêm vào yêu thích' }}">
        <i class="fa fa-heart"></i> 
    </a>

    @if($product->quantity > 0)
        <a href="javascript:void(0)"
           class="btn-cart js-add-cart"
           data-product-id="{{ $product->id }}"
           data-stock="{{ $product->quantity }}"
           title="Thêm vào giỏ hàng">
            <i class="fa fa-shopping-cart"></i> Thêm giỏ hàng
        </a>
    @else
        <span class="btn-cart is-disabled" title="Hết hàng">
            <i class="fa fa-ban"></i> Hết hàng
        </span>
    @endif
</div>
