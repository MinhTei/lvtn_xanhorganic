@if($recipe->products->isEmpty())
    <div class="text-center py-4 text-muted">
        <i class="fa fa-cutlery fa-2x mb-2"></i>
        <p class="mb-0">Món ăn này chưa có nguyên liệu nào.</p>
    </div>
@else
    <div class="recipe-items-list">
        @foreach($recipe->products as $product)
            <div class="d-flex align-items-center justify-content-between border-bottom py-3 recipe-product-item" data-product-id="{{ $product->id }}">
                <div class="d-flex align-items-center" style="gap: 12px;">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}"
                         style="width: 56px; height: 56px; object-fit: cover; border-radius: 6px;">
                    <div>
                        <a href="{{ route('product.detail', $product->slug) }}" class="font-weight-bold text-dark">
                            {{ $product->name }}
                        </a>
                        <div class="text-muted" style="font-size: 13px;">
                            {{ number_format($product->sale_price ?? $product->price, 0, ',', '.') }}₫
                            @if($product->unit)
                                <span>/ {{ $product->unit }}</span>
                            @endif
                            @if($product->sale_price)
                                <del class="ml-1" style="font-size: 12px;">{{ number_format($product->price, 0, ',', '.') }}₫</del>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
@endif
