@extends('layouts.admin')
@section('title', 'Quản lý sản phẩm')

@section('content')
<div class="panel">
    <div class="panel__head">
        <h2>Danh sách sản phẩm</h2>
        <div class="d-flex gap-2">
            @if(auth()->user()->hasPermission('add_products'))
                <a href="{{ route('admin.products.import') }}" class="btn-admin btn-admin-outline">Import CSV</a>
                <a href="{{ route('admin.products.create') }}" class="btn-admin"><i class="fa fa-plus"></i> Thêm sản phẩm</a>
            @endif
        </div>
    </div>

   <div class ="d-flex gap-3 mb-3 " >
    <div class="w-50">
        <a href="{{ route('admin.products.index', ['alert' => 'low_stock']) }}" class="text-decoration-none">
            <div class="p-3 flex-fill text-center" style="background-color: #fff3cd; color: #856404; border: 1px solid #ffeeba; border-radius: 8px; {{ request('alert') == 'low_stock' ? 'box-shadow: 0 0 10px rgba(133,100,4,0.5); font-weight: bold;' : '' }}">
                <h5 class="mb-2" style="font-size: 1rem; font-weight: 600;">Sản phẩm sắp hết hàng (&le; 10)</h5>
                <h3 class="mb-0 fw-bold">{{ $lowStock ?? 0}}</h3>
            </div>
        </a>
    </div>
    <div class="w-50">
        <a href="{{ route('admin.products.index', ['alert' => 'near_expiry']) }}" class="text-decoration-none">
            <div class="p-3 flex-fill text-center" style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 8px; {{ request('alert') == 'near_expiry' ? 'box-shadow: 0 0 10px rgba(114,28,36,0.5); font-weight: bold;' : '' }}">
                <h5 class="mb-2" style="font-size: 1rem; font-weight: 600;">Sản phẩm sắp hết hạn (&le; 5 ngày)</h5>
                <h3 class="mb-0 fw-bold">{{ $lowDate ?? 0}}</h3>
            </div>
        </a>
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
        <!-- <select name="status" class="form-select">
            <option value="">Trạng thái</option>
            <option value="1" @selected(request('status') === '1')>Đang bán</option>
            <option value="0" @selected(request('status') === '0')>Ẩn</option>
        </select> -->
        <select name="alert" class="form-select">
            <option value="">Lọc trạng thái</option>
            <option value="low_stock" @selected(request('alert') === 'low_stock')>Sắp hết hàng (&le; 10)</option>
            <option value="near_expiry" @selected(request('alert') === 'near_expiry')>Sắp hết hạn (&le; 5 ngày)</option>
        </select>
        <button class="btn-admin btn-sm-admin" type="submit">Lọc</button>
        <!-- @if(request()->anyFilled(['q', 'category_id', 'status', 'alert']))
            <a href="{{ route('admin.products.index') }}" class="btn-admin btn-sm-admin btn-admin-outline" style="padding: 6px 12px;">Bỏ lọc</a>
        @endif -->
    </form>

    <div class="table-responsive">
        <table class="table-admin">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Danh mục</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Trạng thái</th>
                    <th>Ngày sản xuất</th>
                    <th>Ngày hết hạn</th>
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
                        <td>
                            {{ $product->manufacture_date?->format('d/m/Y') ?? '' }}
                        </td>
                        <td>
                            {{ $product->expiry_date?->format('d/m/Y') ?? '' }}
                        </td>
                        <td class="text-nowrap">
                            @if(auth()->user()->hasPermission('edit_products'))
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn-admin btn-admin-outline btn-sm-admin">Sửa</a>
                            @endif
                            @if(auth()->user()->hasPermission('delete_products'))
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Xóa sản phẩm này?');">
                                    @csrf @method('DELETE')
                                    <button class="btn-admin btn-admin-danger btn-sm-admin" type="submit">Xóa</button>
                                </form>
                            @endif
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
