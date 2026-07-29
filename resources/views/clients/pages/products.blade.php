@extends('layouts.client')

@section('title', 'Danh sách sản phẩm')
@section('breadcrumb', 'Danh sách sản phẩm')

@section('content')
    <section class="products-page spad">
        <div class="container">
            <div class="row">

                {{-- Sidebar lọc --}}
                <div class="col-12 col-lg-3">
                    <div class="sidebar">
                        <button type="button" class="btn-mobile-filter mb-3"
                            onclick="$('.sidebar__filter').toggleClass('active')">
                            <i class="fa fa-sliders"></i> Bộ lọc
                        </button>

                        <div class="sidebar__filter sidebar__item">
                            <form action="{{ route('products') }}" method="GET" id="filterForm">
                                <input type="hidden" name="category_id" id="hiddenCategory"
                                    value="{{ request('category_id') }}">
                                <input type="hidden" name="sort" id="hiddenSort" value="{{ request('sort') }}">
                                @if(request('q'))
                                    <input type="hidden" name="q" value="{{ request('q') }}">
                                @endif

                                <h4>Danh Mục</h4>
                                <ul>
                                    <li>
                                        <a href="#" class="category-link " data-category_id="">Tất cả danh mục</a>
                                    </li>
                                    @foreach ($categories as $category)
                                        <li>
                                            @if($category->children->isNotEmpty())
                                                <a href="#cat-{{$category->id}}" data-toggle="collapse"
                                                    class="category-link">{{$category->name}} <i class="fa fa-angle-down"></i></a>
                                                <ul class="collapse" id="cat-{{$category->id}}"
                                                    style="border: none; padding: 0 0 0 30px; margin: 0;">
                                                    @foreach($category->children as $child)
                                                        <li><a href="#" class="category-link"
                                                                data-category_id="{{$child->id}}">{{$child->name}}</a></li>
                                                    @endforeach
                                                </ul>
                                            @else
                                                <a href="#" class="category-link"
                                                    data-category_id="{{$category->id}}">{{$category->name}}</a>
                                            @endif
                                        </li>
                                    @endforeach
                                </ul>

                                <div class="filter__group">
                                    <h5>Khoảng giá (₫)</h5>
                                    <div class="price__inputs">
                                        <input type="number" id="min_price" name="min_price" placeholder="Tối thiểu"
                                            value="{{ request('min_price') }}">
                                        <span>-</span>
                                        <input type="number" id="max_price" name="max_price" placeholder="Tối đa"
                                            value="{{ request('max_price') }}">
                                    </div>
                                </div>
                                <button type="button" class="btn btn-success btn-filter">ÁP DỤNG</button>
                                <button type="button" class="btn btn-secondary btn-clear">HỦY</button>
                            </form>
                        </div>
                    </div>
                </div>

                {{-- Danh sách sản phẩm --}}
                <div class="col-12 col-lg-9" id="product-list-area">

                    {{-- Header: tiêu đề + sắp xếp --}}
                    <div class="product__header">
                        <div class="product__header__title">
                            <h2>
                                @if(request('q'))
                                    Kết quả tìm: "{{ request('q') }}"
                                @else
                                    Danh sách sản phẩm
                                @endif
                            </h2>
                            <p>
                                Hiển thị
                                <span>{{ $products->firstItem() ?? 0 }}-{{ $products->lastItem() ?? 0 }}</span>
                                trong tổng số
                                <span>{{ $products->total() }}</span> sản phẩm
                                @if(request('q'))
                                    · <a href="{{ route('products') }}">Xóa tìm kiếm</a>
                                @endif
                            </p>
                        </div>
                        <div class="product__header__sort">
                            <span>Sắp xếp:</span>
                            <select id="sortSelect">
                                <option value="price_asc" @selected(request('sort') == 'price_asc')>Giá thấp đến cao</option>
                                <option value="price_desc" @selected(request('sort') == 'price_desc')>Giá cao đến thấp
                                </option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        @include('clients.partials.products_grid', ['products' => $products])
                    </div>
                    {{-- Phân trang --}}
                    <div class="d-flex justify-content-center">
                        {{ $products->links() }}
                    </div>
                </div>

            </div>
        </div>
    </section>
@endsection