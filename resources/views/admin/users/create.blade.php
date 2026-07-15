@extends('layouts.admin')
@section('title', isset($user) ? 'Sửa người dùng' : 'Thêm người dùng')

@section('content')
<div class="panel" style="max-width:560px;">
    <div class="panel__head">
        <h2>{{ isset($user) ? 'Sửa người dùng' : 'Thêm người dùng' }}</h2>
    </div>
    <form method="POST"
          action="{{ isset($user) ? route('admin.users.update', $user) : route('admin.users.store') }}"
          class="form-admin">
        @csrf
        @if(isset($user)) @method('PUT') @endif

        <div class="form-group">
            <label>Họ tên *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $user->name ?? '') }}" required>
        </div>
        <div class="form-group">
            <label>Email *</label>
            <input type="email" name="email" class="form-control" value="{{ old('email', $user->email ?? '') }}" required>
        </div>
        <div class="form-group">
            <label>Số điện thoại</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone ?? '') }}">
        </div>
        <div class="form-group">
            <label>Mật khẩu {{ isset($user) ? '(để trống nếu không đổi)' : '*' }}</label>
            <input type="password" name="password" class="form-control" {{ isset($user) ? '' : 'required' }}>
        </div>
        <div class="form-group">
            <label>Xác nhận mật khẩu</label>
            <input type="password" name="password_confirmation" class="form-control">
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Role *</label>
                    <select name="role_id" class="form-select" required>
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}" @selected(old('role_id', $user->role_id ?? '') == $role->id)>
                                {{ $role->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Trạng thái *</label>
                    <select name="status" class="form-select" required>
                        @foreach(['pending','active','blocked','deleted'] as $st)
                            <option value="{{ $st }}" @selected(old('status', $user->status ?? 'active') === $st)>{{ $st }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn-admin">Lưu</button>
            <a href="{{ route('admin.users.index') }}" class="btn-admin btn-admin-outline">Hủy</a>
        </div>
    </form>
</div>
@endsection
