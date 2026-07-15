@extends('layouts.admin')
@section('title', 'Quản lý người dùng')

@section('content')
<div class="panel">
    <div class="panel__head">
        <h2>Người dùng</h2>
        <a href="{{ route('admin.users.create') }}" class="btn-admin"><i class="fa fa-plus"></i> Thêm</a>
    </div>

    <form class="filters" method="GET">
        <input type="text" name="q" class="form-control" placeholder="Tên / email / SĐT" value="{{ request('q') }}">
        <select name="role_id" class="form-select">
            <option value="">Tất cả role</option>
            @foreach($roles as $role)
                <option value="{{ $role->id }}" @selected(request('role_id') == $role->id)>{{ $role->name }}</option>
            @endforeach
        </select>
        <select name="status" class="form-select">
            <option value="">Trạng thái</option>
            @foreach(['pending','active','blocked','deleted'] as $st)
                <option value="{{ $st }}" @selected(request('status') === $st)>{{ $st }}</option>
            @endforeach
        </select>
        <button class="btn-admin btn-sm-admin" type="submit">Lọc</button>
    </form>

    <div class="table-responsive">
        <table class="table-admin">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tên</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>TT</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td><strong>{{ $user->name }}</strong><div class="text-muted" style="font-size:.8rem;">{{ $user->phone }}</div></td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge-soft">{{ $user->role->name ?? '—' }}</span></td>
                        <td>
                            @php
                                $badge = match($user->status) {
                                    'active' => 'badge-soft',
                                    'pending' => 'badge-warn',
                                    'blocked', 'deleted' => 'badge-danger',
                                    default => 'badge-muted',
                                };
                            @endphp
                            <span class="{{ $badge }}">{{ $user->status }}</span>
                        </td>
                        <td class="text-nowrap">
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn-admin btn-admin-outline btn-sm-admin">Sửa</a>
                            @if($user->id !== auth()->id())
                            <form action="{{ route('admin.users.destroy', $user) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Vô hiệu hóa tài khoản này?');">
                                @csrf @method('DELETE')
                                <button class="btn-admin btn-admin-danger btn-sm-admin" type="submit">Vô hiệu</button>
                            </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-muted">Không có người dùng.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $users->links() }}</div>
</div>
@endsection
