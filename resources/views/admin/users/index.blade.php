@extends('layouts.admin')
@section('title', 'Quản lý người dùng')

@section('content')
<div class="panel">
    <div class="panel__head">
        <h2>Người dùng</h2>
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
            <option value="active" @selected(request('status') === 'active')>Đang hoạt động</option>
            <option value="blocked" @selected(request('status') === 'blocked')>Đã khóa</option>
            <option value="pending" @selected(request('status') === 'pending')>Chờ kích hoạt</option>
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
                    @php
                        $statusLabel = match($user->status) {
                            'active' => 'Đang hoạt động',
                            'blocked' => 'Đã khóa',
                            'pending' => 'Chờ kích hoạt',
                            default => $user->status,
                        };
                        $badge = match($user->status) {
                            'active' => 'badge-soft',
                            'pending' => 'badge-warn',
                            'blocked' => 'badge-danger',
                            default => 'badge-muted',
                        };
                    @endphp
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td><strong>{{ $user->name }}</strong><div class="text-muted" style="font-size:.8rem;">{{ $user->phone }}</div></td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge-soft">{{ $user->role->name ?? '—' }}</span></td>
                        <td><span class="{{ $badge }}">{{ $statusLabel }}</span></td>
                        <td class="text-nowrap">
                            <a href="{{ route('admin.users.show', $user) }}" class="btn-admin btn-admin-outline btn-sm-admin">Chi tiết</a>
                            <a href="{{ route('admin.users.edit', $user) }}" class="btn-admin btn-admin-outline btn-sm-admin">Gán role</a>
                            @if($user->id !== auth()->id() && in_array($user->status, ['active', 'blocked'], true))
                                <form action="{{ route('admin.users.toggle-block', $user) }}" method="POST" class="d-inline"
                                      onsubmit="return confirm('{{ $user->status === 'active' ? 'Khóa tài khoản này?' : 'Mở khóa tài khoản này?' }}');">
                                    @csrf
                                    @method('PATCH')
                                    @if($user->status === 'active')
                                        <button class="btn-admin btn-admin-danger btn-sm-admin" type="submit">Khóa</button>
                                    @else
                                        <button class="btn-admin btn-sm-admin" type="submit">Mở khóa</button>
                                    @endif
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
