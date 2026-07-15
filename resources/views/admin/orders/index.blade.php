@extends('layouts.admin')
@section('title', 'Quản lý đơn hàng')

@section('content')
<div class="card">
    <div class="card-header bg-white">Danh sách đơn hàng</div>
    <div class="card-body">
        <form class="row g-2 mb-3" method="GET">
            <div class="col-auto">
                <input type="text" name="q" class="form-control form-control-sm" placeholder="Mã / tên / SĐT"
                       value="{{ request('q') }}">
            </div>
            <div class="col-auto">
                <select name="status" class="form-select form-select-sm">
                    <option value="">Tất cả trạng thái</option>
                    @foreach($statuses as $st)
                        <option value="{{ $st }}" @selected(request('status') === $st)>{{ $statusLabels[$st] ?? $st }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-auto">
                <button class="btn btn-sm btn-primary" type="submit">Lọc</button>
            </div>
        </form>

        <div class="table-responsive">
            <table class="table table-bordered table-sm table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>Mã đơn</th>
                        <th>Khách</th>
                        <th>Người nhận</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày đặt</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                        <tr>
                            <td>{{ $order->order_code }}</td>
                            <td>{{ $order->user->name ?? '—' }}</td>
                            <td>
                                {{ $order->shipping_name }}
                                <div class="small text-muted">{{ $order->shipping_phone }}</div>
                            </td>
                            <td>{{ number_format($order->total_amount, 0, ',', '.') }}₫</td>
                            <td><span class="badge text-bg-secondary">{{ $statusLabels[$order->status] ?? $order->status }}</span></td>
                            <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">Chi tiết</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-muted">Chưa có đơn hàng.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $orders->links() }}
    </div>
</div>
@endsection
