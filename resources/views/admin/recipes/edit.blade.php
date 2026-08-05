@extends('layouts.admin')
@section('title', 'Sửa món ăn')

@section('content')
<div class="panel" style="max-width:800px;">
    <div class="panel__head">
        <h2>Sửa món ăn: {{ $recipe->title }}</h2>
    </div>
    <form method="POST" action="{{ route('admin.recipes.update', $recipe->id) }}" enctype="multipart/form-data" class="form-admin">
        @csrf
        @method('PUT')
        
        <div class="form-group mb-3">
            <label>Tên món ăn *</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $recipe->title) }}" required>
        </div>

        <div class="form-group mb-3">
            <label>Hình ảnh món ăn</label>
            @if($recipe->image)
                <div class="mb-2">
                    <img src="{{ asset('storage/'.$recipe->image) }}" width="100" alt="Current Image">
                </div>
            @endif
            <input type="file" name="image" class="form-control" accept="image/*">
            <small class="text-muted">Để trống nếu không muốn đổi ảnh.</small>
        </div>

        <div class="form-group mb-3">
            <label>Chọn nguyên liệu (Sản phẩm)</label>
            <select name="product_ids[]" class="form-select" multiple style="height: 200px;">
                @foreach($products as $product)
                    <option value="{{ $product->id }}" 
                        @if($recipe->products->contains($product->id)) selected @endif>
                        {{ $product->name }} ({{ number_format($product->price) }}đ)
                    </option>
                @endforeach
            </select>
            <small class="text-muted">Nhấn giữ phím Ctrl (hoặc Cmd) để chọn/bỏ chọn nhiều nguyên liệu.</small>
        </div>

        <div class="form-check mb-3">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="cat_active" 
                @checked(old('is_active', $recipe->is_active))>
            <label class="form-check-label" for="cat_active">Đang hoạt động</label>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn-admin">Cập nhật</button>
            <a href="{{ route('admin.recipes.index') }}" class="btn-admin btn-admin-outline">Hủy</a>
        </div>
    </form>
</div>
@endsection
