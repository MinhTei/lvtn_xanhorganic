@extends('layouts.client')

@section('title', 'Sản phẩm khuyến mãi')
@section('breadcrumb', 'Khuyến mãi sốc')

@section('content')
<section class="products-page spad">
    <div class="container">
        <div class="row">
            <div class="col-12" id="product-list-area">
                <div class="product__header">
                    <div class="product__header__title">
                        <h2>Sản phẩm khuyến mãi sốc</h2>
                        <p>Hiển thị <span>{{ $products->total() }}</span> sản phẩm đang giảm giá</p>
                    </div>
                </div>
                <div class="row">
                    @include('clients.partials.products_grid', ['products' => $products])
                </div>
                <div class="d-flex justify-content-center mt-4">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
    </div>
</section>
@endsection