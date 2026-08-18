@extends('layouts.admin')
@section('title', isset($category) ? 'Sửa danh mục' : 'Thêm danh mục')

@section('content')
<div class="panel" style="max-width:560px;">
    <div class="panel__head">
        <h2>{{ isset($category) ? 'Sửa danh mục' : 'Thêm danh mục' }}</h2>
    </div>
    <form method="POST"
          action="{{ isset($category) ? route('admin.categories.update', $category) : route('admin.categories.store') }}"
          class="form-admin">
        @csrf
        @if(isset($category)) @method('PUT') @endif

        <div class="form-group">
            <label>Tên *</label>
            <input type="text" name="name" class="form-control"
                   value="{{ old('name', $category->name ?? '') }}" required>
        </div>

        <div class="form-group">
            <label>Danh mục cha</label>
            <select name="parent_id" class="form-select">
                <option value="">— Không (danh mục gốc) —</option>
                @foreach($parents as $parent)
                    <option value="{{ $parent->id }}"
                        @selected(old('parent_id', $category->parent_id ?? '') == $parent->id)>
                        {{ $parent->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Thời hạn sử dụng (ngày)</label>
            <input type="number" name="shelf_days" class="form-control" min="1"
                   value="{{ old('shelf_days', $category->shelf_days ?? '') }}" placeholder="">
            <small class="text-muted">Để trống nếu danh mục (thịt, cá, rau tươi)</small>
        </div>

        <div class="form-check mb-3">
            <input type="hidden" name="is_active" value="0">
            <input type="checkbox" name="is_active" value="1" class="form-check-input" id="cat_active"
                   @checked(old('is_active', $category->is_active ?? true))>
            <label class="form-check-label" for="cat_active">Đang hoạt động</label>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn-admin">Lưu</button>
            <a href="{{ route('admin.categories.index') }}" class="btn-admin btn-admin-outline">Hủy</a>
        </div>
    </form>
</div>
@endsection