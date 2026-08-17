@extends('layouts.admin')
@section('title', 'Thêm món ăn')

@section('content')
<div class="panel" style="max-width:800px;">
    <div class="panel__head">
        <h2>Thêm món ăn</h2>
    </div>
    <form method="POST" action="{{ route('admin.recipes.store') }}" enctype="multipart/form-data" class="form-admin">
        @csrf
        
        <div class="form-group mb-3">
            <label>Tên món ăn *</label>
            <input type="text" name="title" class="form-control" value="{{ old('title') }}" required>
        </div>

        <div class="form-group mb-3">
            <label>Hình ảnh món ăn</label>
            <input type="file" name="image" class="form-control" accept="image/*">
        </div>

        <div class="form-group mb-3">
            <label>Chọn nguyên liệu (Sản phẩm)</label>
            <select name="product_ids[]" id="ingredients_select" class="form-select" multiple>
                @foreach($products as $product)
                    <option value="{{ $product->id }}">
                        {{ $product->name }} ({{ number_format($product->price) }}đ)
                    </option>
                @endforeach
            </select>
            <small class="text-muted">Gõ để tìm kiếm và nhấp để chọn nhiều nguyên liệu.</small>
        </div>

        <div class="form-check mb-3">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="cat_active" checked>
            <label class="form-check-label" for="cat_active">Đang hoạt động</label>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn-admin">Lưu món ăn</button>
            <a href="{{ route('admin.recipes.index') }}" class="btn-admin btn-admin-outline">Hủy</a>
        </div>
    </form>
</div>


@endsection
