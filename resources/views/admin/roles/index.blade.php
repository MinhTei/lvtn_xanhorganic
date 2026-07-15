@extends('layouts.admin')
@section('title', 'Role & Quyền')

@section('content')
<div class="card">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <span>Danh sách Role</span>
        <a href="{{ route('admin.roles.create') }}" class="btn btn-sm btn-primary">+ Thêm role</a>
    </div>
    <div class="card-body">
        <p class="text-muted small mb-3">
            Role hệ thống (<code>admin</code> / <code>staff</code> / <code>customer</code>) <strong>không thể xóa</strong>.
            Role tùy chỉnh chỉ xóa được khi không còn user.
        </p>

        <div class="table-responsive">
            <table class="table table-bordered table-sm align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Users</th>
                        <th>Quyền</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($roles as $role)
                        <tr>
                            <td>{{ $role->id }}</td>
                            <td><strong>{{ $role->name }}</strong></td>
                            <td>{{ $role->users_count }}</td>
                            <td>
                                @forelse($role->permissions as $perm)
                                    <span class="badge text-bg-light text-dark border me-1 mb-1">{{ $perm->name }}</span>
                                @empty
                                    <span class="text-muted">—</span>
                                @endforelse
                            </td>
                            <td class="text-nowrap">
                                <a href="{{ route('admin.roles.edit', $role) }}" class="btn btn-sm btn-outline-primary">Gán quyền</a>
                                <form action="{{ route('admin.roles.destroy', $role) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('Xóa role {{ $role->name }}?');">
                                    @csrf @method('DELETE')
                                    <button class="btn btn-sm btn-outline-danger" type="submit"
                                        @disabled(in_array($role->name, ['admin','staff','customer'], true) || $role->id === auth()->user()->role_id || $role->users_count > 0)
                                        title="{{ in_array($role->name, ['admin','staff','customer'], true) ? 'Role hệ thống' : ($role->id === auth()->user()->role_id ? 'Không xóa role của bạn' : ($role->users_count > 0 ? 'Còn user thuộc role' : 'Xóa')) }}">
                                        Xóa
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
