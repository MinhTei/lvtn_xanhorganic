@extends('layouts.admin')

@section('title', 'Dashboard')

@section('content')
<!-- Loc  -->
<div class="card mb-3">
    <div class="card-body py-2">
        <form method="GET" action="{{ route('admin.dashboard') }}" class="row g-2 align-items-end">
            <div class="col-auto">
                <label class="form-label small mb-0">Từ ngày</label>
                <input type="text" id="picker-from" name="from" class="form-control form-control-sm" style="width:130px" placeholder="dd/mm/yyyy" value="{{ $from ?? '' }}" required>
            </div>
            <div class="col-auto">
                <label class="form-label small mb-0">Đến ngày</label>
                <input type="text" id="picker-to" name="to" class="form-control form-control-sm" style="width:130px" placeholder="dd/mm/yyyy" value="{{ $to ?? '' }}" required>
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary btn-sm">Lọc</button>
            </div>
            <div class="col-auto">
                <a href="{{ route('admin.dashboard.export.pdf', request()->query()) }}"
                   class="btn btn-outline-danger btn-sm">
                    Xuất PDF
                </a>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script src="https://npmcdn.com/flatpickr/dist/l10n/vn.js"></script>
<script>
    const fpConfig = {
        dateFormat: 'Y-m-d',
        altInput: true,
        altFormat: 'd/m/Y',
        locale: 'vn',
        allowInput: true,
    };
    flatpickr('#picker-from', fpConfig);
    flatpickr('#picker-to', fpConfig);
</script>
@endpush

{{-- Thống kê  --}}
<div class="row g-3 mb-3">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="text-muted small">Tổng doanh thu</div>
                <div class="fs-4 fw-semibold">{{ number_format($stats['revenue'], 0, ',', '.') }}₫</div>
                <div class="small text-muted">(Không tính đơn đã hủy)</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="text-muted small">Số đơn hàng</div>
                <div class="fs-4 fw-semibold">{{ $stats['orders'] }}</div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-body">
                <div class="text-muted small">Tổng người dùng</div>
                <div class="fs-4 fw-semibold">{{ $stats['users'] }}</div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-3">
    <div class="col-lg-5">
        <div class="card h-100">
            <div class="card-header bg-white">Trạng thái đơn hàng</div>
            <div class="card-body">
                <div class="chart-box">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header bg-white">Doanh thu theo ngày</div>
            <div class="card-body">
                <div class="chart-box">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-white">Top 10 sản phẩm bán chạy</div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    
                    <table class="table table-sm table-hover mb-0">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Sản phẩm</th>
                                <th>SL bán</th>
                                <th>Doanh thu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($topProducts as $i => $item)
                                <tr>
                                    <td>{{ $i + 1 }}</td>
                                    <td>{{ $item->product_name }}</td>
                                    <td>{{ $item->sold_qty }}</td>
                                    <td>{{ number_format($item->sold_amount, 0, ',', '.') }}₫</td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-muted p-3">Chưa có dữ liệu bán hàng trong khoảng này.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <span>Đơn gần đây</span>
                @if(auth()->user()->hasPermission('manage_orders'))
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">Xem tất cả</a>
                @endif
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-sm table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Mã</th>
                                <th>Khách</th>
                                <th>Tổng</th>
                                <th>TT</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td>
                                        @if(auth()->user()->hasPermission('manage_orders'))
                                            <a href="{{ route('admin.orders.show', $order) }}">{{ $order->order_code }}</a>
                                        @else
                                            {{ $order->order_code }}
                                        @endif
                                    </td>
                                    <td>{{ $order->user->name ?? '—' }}</td>
                                    <td>{{ number_format($order->total_amount, 0, ',', '.') }}₫</td>
                                    <td><span class="badge text-bg-secondary">{{ $order->status }}</span></td>
                                </tr>
                            @empty
                                <tr><td colspan="4" class="text-muted p-3">Chưa có đơn.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
<script>
new Chart(document.getElementById('statusChart'), {
    type: 'doughnut',
    data: {
        labels: @json($statusChart['labels']),
        datasets: [{
            data: @json($statusChart['data']),
            backgroundColor: ['#ffc107', '#0d6efd', '#0dcaf0', '#198754', '#dc3545']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: { legend: { position: 'bottom' } }
    }
});

new Chart(document.getElementById('revenueChart'), {
    type: 'bar',
    data: {
        labels: @json($revenueChart['labels']),
        datasets: [{
            label: 'Doanh thu (₫)',
            data: @json($revenueChart['data']),
            backgroundColor: 'rgba(13, 110, 253, 0.7)'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    callback: function (v) {
                        return new Intl.NumberFormat('vi-VN').format(v);
                    }
                }
            }
        },
        plugins: { legend: { display: false } }
    }
});
</script>
@endpush
