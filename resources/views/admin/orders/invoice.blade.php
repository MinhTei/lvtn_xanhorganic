<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Hóa đơn #{{ $order->order_code }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
        h1 { font-size: 18px; margin: 0 0 4px; color: #7cac34; }
        h2 { font-size: 14px; margin: 18px 0 8px; }
        .muted { color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 8px; }
        th, td { border: 1px solid #ddd; padding: 6px 8px; text-align: left; }
        th { background: #f5f5f5; }
        .right { text-align: right; }
        .totals td { border: none; padding: 3px 0; }
        .brand { margin-bottom: 20px; }
    </style>
</head>
<body>
    <div class="brand">
        <h1>XANH ORGANIC</h1>
        <div class="muted">Thực phẩm hữu cơ — Sống xanh sống khỏe</div>
    </div>

    <h2>HÓA ĐƠN BÁN HÀNG</h2>
    <p>
        <strong>Mã đơn:</strong> {{ $order->order_code }}<br>
        <strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}<br>
        <strong>Trạng thái:</strong> {{ $statusLabels[$order->status] ?? $order->status }}<br>
        <strong>Thanh toán:</strong>
        {{ ($order->orderPayment->payment_method ?? 'COD') === 'VNPay' ? 'VNPay' : 'COD' }}
        /
        @php $ps = $order->orderPayment->payment_status ?? 'pending'; @endphp
        {{ $ps === 'completed' ? 'Đã thanh toán' : ($ps === 'failed' ? 'Thất bại' : 'Chờ thanh toán') }}
    </p>

    <h2>Thông tin giao hàng</h2>
    <p>
        {{ $order->shipping_name }} — {{ $order->shipping_phone }}<br>
        {{ $order->shipping_address }}<br>
        @if($order->user)
            Email: {{ $order->user->email }}
        @endif
    </p>

    <h2>Chi tiết sản phẩm</h2>
    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Sản phẩm</th>
                <th class="right">SL</th>
                <th class="right">Đơn giá</th>
                <th class="right">Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $i => $item)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $item->product_name }}</td>
                    <td class="right">{{ $item->quantity }}</td>
                    <td class="right">{{ number_format($item->unit_price, 0, ',', '.') }}₫</td>
                    <td class="right">{{ number_format($item->subtotal ?? ($item->unit_price * $item->quantity), 0, ',', '.') }}₫</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <table class="totals" style="margin-top: 16px; width: 280px; margin-left: auto;">
        <tr>
            <td>Tạm tính</td>
            <td class="right">{{ number_format($order->subtotal, 0, ',', '.') }}₫</td>
        </tr>
        @if($order->discount_amount > 0)
            <tr>
                <td>Giảm giá @if($order->coupon_code)({{ $order->coupon_code }})@endif</td>
                <td class="right">−{{ number_format($order->discount_amount, 0, ',', '.') }}₫</td>
            </tr>
        @endif
        <tr>
            <td>Phí vận chuyển</td>
            <td class="right">{{ $order->shipping_fee == 0 ? 'Miễn phí' : number_format($order->shipping_fee, 0, ',', '.') . '₫' }}</td>
        </tr>
        <tr>
            <td><strong>Tổng cộng</strong></td>
            <td class="right"><strong>{{ number_format($order->total_amount, 0, ',', '.') }}₫</strong></td>
        </tr>
    </table>

    <p class="muted" style="margin-top: 28px;">In lúc {{ now()->format('d/m/Y H:i') }} — Xanh Organic</p>
</body>
</html>
