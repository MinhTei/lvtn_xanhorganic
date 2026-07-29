@extends('layouts.admin')
@section('title', 'Gán role')

@section('content')
<div class="panel" style="max-width:560px;">
    <div class="panel__head">
        <h2>Gán role</h2>
    </div>

    <div class="form-admin mb-3">
        <div class="row mb-2">
            <div class="col-sm-4 text-muted">Họ tên</div>
            <div class="col-sm-8"><strong>{{ $user->name }}</strong></div>
        </div>
        <div class="row mb-2">
            <div class="col-sm-4 text-muted">Email</div>
            <div class="col-sm-8">{{ $user->email }}</div>
        </div>
        <div class="row mb-2">
            <div class="col-sm-4 text-muted">Role hiện tại</div>
            <div class="col-sm-8"><span class="badge-soft">{{ $user->role->name ?? '—' }}</span></div>
        </div>
    </div>

    <form method="POST" action="{{ route('admin.users.update', $user) }}" class="form-admin">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Role *</label>
            <select name="role_id" class="form-select" required>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}" @selected(old('role_id', $user->role_id) == $role->id)>
                        {{ $role->name }}
                    </option>
                @endforeach
            </select>
            <small class="text-muted">Thông tin cá nhân do người dùng tự cập nhật ở trang Tài khoản.</small>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn-admin">Lưu role</button>
            <a href="{{ route('admin.users.show', $user) }}" class="btn-admin btn-admin-outline">Quay lại</a>
        </div>
    </form>
</div>
@endsection
