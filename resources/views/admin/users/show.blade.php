@extends('layouts.admin')
@section('title', 'Chi tiết người dùng')

@section('content')
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

<div class="mb-2 d-flex gap-2 flex-wrap">
    <a href="{{ route('admin.users.index') }}" class="btn-admin btn-admin-outline btn-sm-admin">&larr; Quay lại</a>
    <a href="{{ route('admin.users.edit', $user) }}" class="btn-admin btn-sm-admin">Gán role</a>
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
</div>

<div class="panel" style="max-width:720px;">
    <div class="panel__head">
        <h2>Thông tin tài khoản #{{ $user->id }}</h2>
    </div>

    <div class="form-admin">
        <div class="row mb-2">
            <div class="col-sm-4 text-muted">Họ tên</div>
            <div class="col-sm-8"><strong>{{ $user->name }}</strong></div>
        </div>
        <div class="row mb-2">
            <div class="col-sm-4 text-muted">Email</div>
            <div class="col-sm-8">{{ $user->email }}</div>
        </div>
        <div class="row mb-2">
            <div class="col-sm-4 text-muted">Số điện thoại</div>
            <div class="col-sm-8">{{ $user->phone ?: '—' }}</div>
        </div>
        <div class="row mb-2">
            <div class="col-sm-4 text-muted">Vai trò</div>
            <div class="col-sm-8"><span class="badge-soft">{{ $user->role->name ?? '—' }}</span></div>
        </div>
        <div class="row mb-2">
            <div class="col-sm-4 text-muted">Trạng thái</div>
            <div class="col-sm-8"><span class="{{ $badge }}">{{ $statusLabel }}</span></div>
        </div>
        <div class="row mb-2">
            <div class="col-sm-4 text-muted">Số đơn hàng</div>
            <div class="col-sm-8">{{ $orderCount }}</div>
        </div>
        <div class="row mb-2">
            <div class="col-sm-4 text-muted">Địa chỉ đã lưu</div>
            <div class="col-sm-8">{{ $user->addresses->count() }}</div>
        </div>
        <div class="row mb-2">
            <div class="col-sm-4 text-muted">Ngày tạo</div>
            <div class="col-sm-8">{{ $user->created_at?->format('d/m/Y H:i') ?? '—' }}</div>
        </div>
        <div class="row mb-3">
            <div class="col-sm-4 text-muted">Cập nhật gần nhất</div>
            <div class="col-sm-8">{{ $user->updated_at?->format('d/m/Y H:i') ?? '—' }}</div>
        </div>

        @if($user->addresses->isNotEmpty())
            <h5 class="mt-3 mb-2">Địa chỉ giao hàng</h5>
            <ul class="list-unstyled mb-3">
                @foreach($user->addresses as $address)
                    <li class="mb-2 small">
                        @if($address->is_default)<span class="badge-soft">Mặc định</span>@endif
                        {{ $address->receiver_name }} — {{ $address->receiver_phone }}<br>
                        {{ $address->street_address }}, {{ $address->ward }}, {{ $address->district }}, {{ $address->province }}
                    </li>
                @endforeach
            </ul>
        @endif

        @if($recentOrders->isNotEmpty())
            <h5 class="mt-3 mb-2">Đơn hàng gần đây</h5>
            <div class="table-responsive">
                <table class="table-admin">
                    <thead>
                        <tr>
                            <th>Mã đơn</th>
                            <th>Trạng thái</th>
                            <th>Tổng</th>
                            <th>Ngày</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($recentOrders as $order)
                            <tr>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}">{{ $order->order_code }}</a>
                                </td>
                                <td>{{ $order->statusLabel() }}</td>
                                <td>{{ number_format($order->total_amount, 0, ',', '.') }}₫</td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
