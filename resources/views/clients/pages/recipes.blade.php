@extends('layouts.client')

@section('title', 'Món ngon mỗi ngày')
@section('breadcrumb', 'Góc ẩm thực')

@section('content')
<section class="products-page spad">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="product__header">
                    <div class="product__header__title">
                        <h2>Góc Ẩm Thực - Món ngon mỗi ngày</h2>
                        <p>Khám phá các công thức và dễ dàng mua sắm nguyên liệu chuẩn bị cho bữa ăn của bạn.</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            @forelse($recipes as $recipe)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 shadow-sm border-0">
                        <img src="{{ $recipe->image ? asset('storage/'.$recipe->image) : asset('assets/clients/img/no-image.jpg') }}"
                             class="card-img-top" alt="{{ $recipe->title }}" style="height: 200px; object-fit: cover;">
                        <div class="card-body text-center d-flex flex-column">
                            <h5 class="card-title text-success font-weight-bold">{{ $recipe->title }}</h5>
                            <button type="button" class="btn btn-outline-success mt-auto btn-show-recipe"
                                    data-id="{{ $recipe->id }}" data-title="{{ $recipe->title }}">
                                MUA NGUYÊN LIỆU
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center">
                    <p>Chưa có món ăn nào được cập nhật.</p>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-4">
            {{ $recipes->links() }}
        </div>
    </div>
</section>

@include('clients.components.modals.recipe_items')

@endsection



