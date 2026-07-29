@extends('layouts.admin')
@section('title', 'Đơn hàng ' . $order->order_code)

@section('content')
<div class="mb-2 d-flex gap-2 flex-wrap">
    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-secondary">&larr; Quay lại</a>
    <a href="{{ route('admin.orders.invoice', $order) }}" target="_blank" class="btn btn-sm btn-outline-primary">
        In hóa đơn
    </a>
</div>

<div class="row g-3">
    <div class="col-lg-8">
        <div class="card mb-3">
            <div class="card-header bg-white">Thông tin đơn #{{ $order->order_code }}</div>
            <div class="card-body">
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Khách hàng</div>
                    <div class="col-sm-8">{{ $order->user->name ?? '—' }} ({{ $order->user->email ?? '' }})</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Người nhận</div>
                    <div class="col-sm-8">{{ $order->shipping_name }} — {{ $order->shipping_phone }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Địa chỉ</div>
                    <div class="col-sm-8">{{ $order->shipping_address }}</div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Thanh toán</div>
                    <div class="col-sm-8">
                        @php
                            $pm = $order->orderPayment->payment_method ?? '—';
                            $ps = $order->orderPayment->payment_status ?? '—';
                        @endphp
                        {{ $pm === 'VNPay' ? 'VNPay' : ($pm === 'COD' ? 'COD' : $pm) }}
                        @if($ps)
                            ·
                            @if($ps === 'completed') Đã thanh toán
                            @elseif($ps === 'failed') Thất bại
                            @else Chờ thanh toán
                            @endif
                        @endif
                        @if(!empty($order->orderPayment?->transaction_id))
                            · GD: {{ $order->orderPayment->transaction_id }}
                        @endif
                        / {{ $order->orderPayment->payment_status ?? '—' }}
                    </div>
                </div>
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Giao hàng</div>
                    <div class="col-sm-8">
                        @if(($order->shipping_type ?? 'standard') === 'express')
                            Giao nhanh trong 2 giờ
                        @elseif($order->delivery_slot)
                            Giao theo khung giờ
                        @else
                            Giao hàng thường (3–5 ngày)
                        @endif
                        @if($order->delivery_slot)
                            · {{ \App\Services\DeliveryService::labelForSlot($order->delivery_slot) }}
                        @endif
                    </div>
                </div>
                @if($order->coupon_code)
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Mã giảm giá</div>
                    <div class="col-sm-8">{{ $order->coupon_code }} (−{{ number_format((float)$order->discount_amount, 0, ',', '.') }}₫)</div>
                </div>
                @endif
                <div class="row mb-2">
                    <div class="col-sm-4 text-muted">Ghi chú</div>
                    <div class="col-sm-8">{{ $order->note ?: '—' }}</div>
                </div>
                <div class="row">
                    <div class="col-sm-4 text-muted">Ngày đặt</div>
                    <div class="col-sm-8">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                </div>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header bg-white">Sản phẩm</div>
            <div class="card-body p-0">
                <table class="table table-sm mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>SP</th>
                            <th>SL</th>
                            <th>Đơn giá</th>
                            <th>Thành tiền</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->orderItems as $item)
                            <tr>
                                <td>{{ $item->product_name }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>{{ number_format($item->unit_price, 0, ',', '.') }}₫</td>
                                <td>{{ number_format($item->subtotal, 0, ',', '.') }}₫</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-end">Tạm tính</td>
                            <td>{{ number_format($order->subtotal, 0, ',', '.') }}₫</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end">Ship</td>
                            <td>{{ number_format($order->shipping_fee, 0, ',', '.') }}₫</td>
                        </tr>
                        <tr>
                            <td colspan="3" class="text-end fw-semibold">Tổng</td>
                            <td class="fw-semibold">{{ number_format($order->total_amount, 0, ',', '.') }}₫</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white">Lịch sử trạng thái</div>
            <ul class="list-group list-group-flush">
                @forelse($order->orderStatusLogs->sortByDesc('id') as $log)
                    <li class="list-group-item small">
                        <strong>{{ $statusLabels[$log->old_status] ?? $log->old_status }}</strong>
                        →
                        <strong>{{ $statusLabels[$log->new_status] ?? $log->new_status }}</strong>
                        <span class="text-muted">· {{ $log->created_at->format('d/m/Y H:i') }}</span>
                        @if($log->note)
                            <div class="text-muted">{{ $log->note }}</div>
                        @endif
                    </li>
                @empty
                    <li class="list-group-item text-muted">Chưa có lịch sử.</li>
                @endforelse
            </ul>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card mb-3">
            <div class="card-header bg-white">Luồng trạng thái</div>
            <div class="card-body small">
                <ol class="mb-2 ps-3">
                    <li>Chờ xác nhận</li>
                    <li>Đã xác nhận <span class="text-muted">(kiểm tra tồn kho)</span></li>
                    <li>Đang giao</li>
                    <li>Đã giao</li>
                </ol>
                <div class="text-muted">Hủy chỉ khi đang <em>Chờ xác nhận</em> hoặc <em>Đã xác nhận</em>. Không nhảy bước / không quay ngược.</div>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white">Cập nhật trạng thái</div>
            <div class="card-body">
                <p>
                    Hiện tại:
                    <span class="badge text-bg-primary">{{ $order->statusLabel() }}</span>
                </p>

                @if(count($nextStatuses) === 0)
                    <div class="alert alert-secondary py-2 mb-0">
                        Đơn đã ở trạng thái cuối — không thể thay đổi thêm.
                    </div>
                @else
                    <form method="POST" action="{{ route('admin.orders.status', $order) }}">
                        @csrf @method('PATCH')
                        <div class="mb-2">
                            <label class="form-label">Bước tiếp theo</label>
                            <select name="status" class="form-select" required>
                                @foreach($nextStatuses as $st)
                                    <option value="{{ $st }}">{{ $statusLabels[$st] ?? $st }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Ghi chú</label>
                            <textarea name="note" class="form-control" rows="2" placeholder="Tùy chọn"></textarea>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Lưu trạng thái</button>
                    </form>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
