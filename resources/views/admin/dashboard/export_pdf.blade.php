<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Báo cáo Dashboard</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; color: #222; }
        h1 { font-size: 18px; color: #7cac34; margin-bottom: 4px; }
        h2 { font-size: 14px; margin: 18px 0 8px; border-bottom: 1px solid #ddd; padding-bottom: 4px; }
        .muted { color: #666; }
        table { width: 100%; border-collapse: collapse; margin-top: 6px; }
        th, td { border: 1px solid #ddd; padding: 5px 7px; text-align: left; }
        th { background: #f5f5f5; }
        .right { text-align: right; }
        .stats td { border: none; padding: 3px 0; }
    </style>
</head>
<body>
    <h1>BÁO CÁO DASHBOARD — XANH ORGANIC</h1>
    <p class="muted">
        Khoảng thời gian: {{ $from->format('d/m/Y') }} → {{ $to->format('d/m/Y') }}
        · Loại lọc: {{ $range }}
        · Xuất lúc: {{ now()->format('d/m/Y H:i') }}
    </p>

    <h2>Tổng quan</h2>
    <table class="stats" style="width: 360px;">
        <tr><td>Tổng doanh thu (không tính hủy)</td><td class="right"><strong>{{ number_format($stats['revenue'], 0, ',', '.') }}₫</strong></td></tr>
        <tr><td>Số đơn hàng</td><td class="right">{{ $stats['orders'] }}</td></tr>
        <tr><td>Tổng người dùng</td><td class="right">{{ $stats['users'] }}</td></tr>
    </table>

    <h2>Phân bố trạng thái đơn</h2>
    <table>
        <thead><tr><th>Trạng thái</th><th class="right">Số đơn</th></tr></thead>
        <tbody>
            @foreach($statusChart['labels'] as $i => $label)
                <tr>
                    <td>{{ $label }}</td>
                    <td class="right">{{ $statusChart['data'][$i] }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Doanh thu theo ngày</h2>
    <table>
        <thead><tr><th>Ngày</th><th class="right">Doanh thu (₫)</th></tr></thead>
        <tbody>
            @foreach($revenueChart['labels'] as $i => $day)
                <tr>
                    <td>{{ $day }}</td>
                    <td class="right">{{ number_format($revenueChart['data'][$i], 0, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Top 10 sản phẩm bán chạy</h2>
    <table>
        <thead>
            <tr>
                <th>STT</th>
                <th>Sản phẩm</th>
                <th class="right">SL bán</th>
                <th class="right">Doanh thu (₫)</th>
            </tr>
        </thead>
        <tbody>
            @forelse($topProducts as $i => $row)
                <tr>
                    <td>{{ $i + 1 }}</td>
                    <td>{{ $row->product_name }}</td>
                    <td class="right">{{ $row->sold_qty }}</td>
                    <td class="right">{{ number_format($row->sold_amount, 0, ',', '.') }}</td>
                </tr>
            @empty
                <tr><td colspan="4">Không có dữ liệu.</td></tr>
            @endforelse
        </tbody>
    </table>
</body>
</html>
