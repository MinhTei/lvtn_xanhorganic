@extends('layouts.admin')
@section('title', 'Quản lý danh mục')

@section('content')
<div class="panel">
    <div class="panel__head">
        <h2>Danh mục</h2>
        <a href="{{ route('admin.categories.create') }}" class="btn-admin"><i class="fa fa-plus"></i> Thêm</a>
    </div>
    <div class="table-responsive">
        <table class="table-admin">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Cha</th>
                    <th>Slug</th>
                    <th>SP</th>
                    <th>TT</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td><strong>{{ $category->name }}</strong></td>
                        <td>{{ $category->parent->name ?? '—' }}</td>
                        <td>{{ $category->slug }}</td>
                        <td>{{ $category->products_count }}</td>
                        <td>
                            @if($category->is_active)
                                <span class="badge-soft">Hiện</span>
                            @else
                                <span class="badge-muted">Ẩn</span>
                            @endif
                        </td>
                        <td class="text-nowrap">
                            <a href="{{ route('admin.categories.edit', $category) }}" class="btn-admin btn-admin-outline btn-sm-admin">Sửa</a>
                            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Xóa danh mục này?');">
                                @csrf @method('DELETE')
                                <button class="btn-admin btn-admin-danger btn-sm-admin" type="submit">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-muted">Chưa có danh mục.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $categories->links() }}</div>
</div>
@endsection
