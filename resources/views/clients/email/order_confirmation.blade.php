<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Xác nhận đơn hàng #{{ $order->order_code }}</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6; max-width: 640px; margin: 0 auto; padding: 20px;">
    <h2 style="color: #7cac34;">Xin chào {{ $order->shipping_name ?: ($order->user->name ?? 'Quý khách') }}!</h2>
    <p>Cảm ơn bạn đã đặt hàng tại <strong>Xanh Organic</strong>. Hệ thống đã ghi nhận đơn hàng của bạn.</p>

    <p>
        <strong>Mã đơn hàng:</strong> {{ $order->order_code }}<br>
        <strong>Trạng thái:</strong> Chờ xác nhận<br>
        <strong>Ngày đặt:</strong> {{ $order->created_at->format('d/m/Y H:i') }}<br>
        <strong>Thanh toán:</strong>
        {{ ($order->orderPayment->payment_method ?? 'COD') === 'VNPay' ? 'VNPay' : 'COD (thanh toán khi nhận hàng)' }}
    </p>

    <h3 style="border-bottom: 1px solid #eee; padding-bottom: 8px;">Sản phẩm</h3>
    <table width="100%" cellpadding="8" cellspacing="0" style="border-collapse: collapse;">
        <thead>
            <tr style="background: #f5f5f5; text-align: left;">
                <th>Sản phẩm</th>
                <th>SL</th>
                <th>Đơn giá</th>
                <th>Thành tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->orderItems as $item)
                <tr style="border-bottom: 1px solid #eee;">
                    <td>{{ $item->product_name }}</td>
                    <td>{{ $item->quantity }}</td>
                    <td>{{ number_format($item->unit_price, 0, ',', '.') }}₫</td>
                    <td>{{ number_format($item->subtotal ?? ($item->unit_price * $item->quantity), 0, ',', '.') }}₫</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p style="margin-top: 16px;">
        Tạm tính: {{ number_format($order->subtotal, 0, ',', '.') }}₫<br>
        @if($order->discount_amount > 0)
            Giảm giá: −{{ number_format($order->discount_amount, 0, ',', '.') }}₫<br>
        @endif
        Phí vận chuyển: {{ $order->shipping_fee == 0 ? 'Miễn phí' : number_format($order->shipping_fee, 0, ',', '.') . '₫' }}<br>
        <strong>Tổng cộng: {{ number_format($order->total_amount, 0, ',', '.') }}₫</strong>
    </p>

    <p>
        <strong>Giao đến:</strong><br>
        {{ $order->shipping_name }} — {{ $order->shipping_phone }}<br>
        {{ $order->shipping_address }}
    </p>

    <p>Bạn có thể theo dõi đơn hàng trong mục <strong>Tài khoản → Lịch sử đơn hàng</strong>.</p>
    <p>Trân trọng,<br>Đội ngũ Xanh Organic</p>
</body>
</html>
