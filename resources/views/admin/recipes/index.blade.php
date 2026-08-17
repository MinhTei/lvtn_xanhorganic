@extends('layouts.admin')
@section('title', 'Quản lý Món ăn')

@section('content')
<div class="panel">
    <div class="panel__head">
        <h2>Món ăn</h2>
        @if(auth()->user()->hasPermission('add_recipes'))
            <a href="{{ route('admin.recipes.create') }}" class="btn-admin"><i class="fa fa-plus"></i> Thêm</a>
        @endif
    </div>
    <div class="table-responsive">
        <table class="table-admin">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Hình ảnh</th>
                    <th>Tên món ăn</th>
                    <th>Trạng thái</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($recipes as $recipe)
                    <tr>
                        <td>{{ $recipe->id }}</td>
                        <td>
                            @if($recipe->image)
                                <img src="{{ asset($recipe->image) }}" width="60" alt="{{ $recipe->title }}">
                            @else
                                <span class="text-muted">Không có</span>
                            @endif
                        </td>
                        <td><strong>{{ $recipe->title }}</strong></td>
                        <td>
                            @if($recipe->is_active)
                                <span class="badge-soft">Hiện</span>
                            @else
                                <span class="badge-muted">Ẩn</span>
                            @endif
                        </td>
                        <td class="text-nowrap">
                            @if(auth()->user()->hasPermission('edit_recipes'))
                                <a href="{{ route('admin.recipes.edit', $recipe->id) }}" class="btn-admin btn-admin-outline btn-sm-admin">Sửa</a>
                            @endif
                            @if(auth()->user()->hasPermission('delete_recipes'))
                                <form action="{{ route('admin.recipes.destroy', $recipe->id) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Xóa món ăn này?');">
                                    @csrf @method('DELETE')
                                    <button class="btn-admin btn-admin-danger btn-sm-admin" type="submit">Xóa</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="5" class="text-muted">Chưa có món ăn.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $recipes->links() }}</div>
</div>
@endsection
