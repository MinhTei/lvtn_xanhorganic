@extends('layouts.admin')
@section('title', 'Thêm role')

@section('content')
<div class="card" style="max-width:560px;">
    <div class="card-header bg-white">Thêm role mới</div>
    <div class="card-body">
        <form method="POST" action="{{ route('admin.roles.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Tên role *</label>
                <input type="text" name="name" class="form-control" value="{{ old('name') }}"
                       placeholder="vd: warehouse" required>
                <div class="form-text">Chữ thường, không dấu cách (dùng _ hoặc -).</div>
            </div>

            <div class="mb-3">
                <label class="form-label">Quyền</label>
                @foreach($permissions as $permission)
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="permissions[]"
                               value="{{ $permission->id }}" id="perm_{{ $permission->id }}"
                               @checked(collect(old('permissions', []))->contains($permission->id))>
                        <label class="form-check-label" for="perm_{{ $permission->id }}">{{ $permission->name }}</label>
                    </div>
                @endforeach
            </div>

            <button type="submit" class="btn btn-primary">Lưu</button>
            <a href="{{ route('admin.roles.index') }}" class="btn btn-outline-secondary">Hủy</a>
        </form>
    </div>
</div>
@endsection
