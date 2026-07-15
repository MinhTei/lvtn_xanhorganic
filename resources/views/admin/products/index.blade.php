@extends('layouts.admin')
@section('title', 'Quản lý sản phẩm')

@section('content')
<div class="panel">
    <div class="panel__head">
        <h2>Danh sách sản phẩm</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.import') }}" class="btn-admin btn-admin-outline">Import CSV</a>
            <a href="{{ route('admin.products.create') }}" class="btn-admin"><i class="fa fa-plus"></i> Thêm sản phẩm</a>
        </div>
    </div>

    <form class="filters" method="GET">
        <input type="text" name="q" class="form-control" placeholder="Tìm tên / slug..." value="{{ request('q') }}">
        <select name="category_id" class="form-select">
            <option value="">Tất cả danh mục</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->id }}" @selected(request('category_id') == $cat->id)>{{ $cat->name }}</option>
            @endforeach
        </select>
        <select name="status" class="form-select">
            <option value="">Trạng thái</option>
            <option value="1" @selected(request('status') === '1')>Đang bán</option>
            <option value="0" @selected(request('status') === '0')>Ẩn</option>
        </select>
        <button class="btn-admin btn-sm-admin" type="submit">Lọc</button>
    </form>

    <div class="table-responsive">
        <table class="table-admin">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Danh mục</th>
                    <th>Giá</th>
                    <th>Kho</th>
                    <th>TT</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>
                            <strong>{{ $product->name }}</strong>
                            @if($product->is_featured)<span class="badge-soft ms-1">Nổi bật</span>@endif
                        </td>
                        <td>{{ $product->category->name ?? '—' }}</td>
                        <td>
                            {{ number_format($product->sale_price ?? $product->price, 0, ',', '.') }}₫
                            @if($product->sale_price)
                                <small class="text-muted text-decoration-line-through d-block">{{ number_format($product->price, 0, ',', '.') }}₫</small>
                            @endif
                        </td>
                        <td>{{ $product->quantity }}</td>
                        <td>
                            @if($product->is_active)
                                <span class="badge-soft">Hiện</span>
                            @else
                                <span class="badge-muted">Ẩn</span>
                            @endif
                        </td>
                        <td class="text-nowrap">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn-admin btn-admin-outline btn-sm-admin">Sửa</a>
                            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Xóa sản phẩm này?');">
                                @csrf @method('DELETE')
                                <button class="btn-admin btn-admin-danger btn-sm-admin" type="submit">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-muted">Chưa có sản phẩm.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $products->links() }}</div>
</div>
@endsection
