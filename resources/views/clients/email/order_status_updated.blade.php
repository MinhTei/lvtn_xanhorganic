<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Cập nhật đơn #{{ $order->order_code }}</title>
</head>
<body style="font-family: Arial, sans-serif; color: #333; line-height: 1.6; max-width: 640px; margin: 0 auto; padding: 20px;">
    <h2 style="color: #7cac34;">Xin chào {{ $order->shipping_name ?: ($order->user->name ?? 'Quý khách') }}!</h2>
    <p>Đơn hàng <strong>#{{ $order->order_code }}</strong> của bạn vừa được cập nhật trạng thái.</p>
    <p>
        <strong>Trạng thái cũ:</strong> {{ $statusLabels[$oldStatus] ?? $oldStatus }}<br>
        <strong>Trạng thái mới:</strong> {{ $statusLabels[$newStatus] ?? $newStatus }}
    </p>
    <p>Tổng thanh toán: <strong>{{ number_format($order->total_amount, 0, ',', '.') }}₫</strong></p>
    <p style="color: #666; font-size: 13px;">Cảm ơn bạn đã mua sắm tại Xanh Organic.</p>
</body>
</html>
