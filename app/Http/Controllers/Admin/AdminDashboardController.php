<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Dashboard: thống kê + biểu đồ theo khoảng thời gian + xuất báo cáo CSV.
 */
class AdminDashboardController extends Controller
{
    public function index(Request $request)
    {
        [$from, $to, $range] = $this->resolveDateRange($request);
        $data = $this->buildReportData($from, $to);

        return view('admin.dashboard.index', array_merge($data, [
            'range' => $range,
            'from' => $from->format('Y-m-d'),
            'to' => $to->format('Y-m-d'),
        ]));
    }

    /**
     * Xuất báo cáo CSV theo cùng bộ lọc thời gian với dashboard.
     */
    public function export(Request $request): StreamedResponse
    {
        [$from, $to, $range] = $this->resolveDateRange($request);
        $data = $this->buildReportData($from, $to);

        $filename = 'bao-cao-dashboard_'.$from->format('Ymd').'_'.$to->format('Ymd').'.csv';

        return response()->streamDownload(function () use ($from, $to, $range, $data) {
            $out = fopen('php://output', 'w');
            // BOM UTF-8 để Excel mở đúng tiếng Việt
            fwrite($out, "\xEF\xBB\xBF");

            fputcsv($out, ['BÁO CÁO DASHBOARD — XANH ORGANIC']);
            fputcsv($out, ['Khoảng thời gian', $from->format('d/m/Y').' → '.$to->format('d/m/Y')]);
            fputcsv($out, ['Loại lọc', $range]);
            fputcsv($out, ['Xuất lúc', now()->format('d/m/Y H:i')]);
            fputcsv($out, []);

            fputcsv($out, ['TỔNG QUAN']);
            fputcsv($out, ['Chỉ số', 'Giá trị']);
            fputcsv($out, ['Tổng doanh thu (không tính hủy)', number_format($data['stats']['revenue'], 0, ',', '.').' VND']);
            fputcsv($out, ['Số đơn hàng', $data['stats']['orders']]);
            fputcsv($out, ['Tổng sản phẩm trong kho', $data['stats']['products']]);
            fputcsv($out, []);

            fputcsv($out, ['PHÂN BỐ TRẠNG THÁI ĐƠN']);
            fputcsv($out, ['Trạng thái', 'Số đơn']);
            foreach ($data['statusChart']['labels'] as $i => $label) {
                fputcsv($out, [$label, $data['statusChart']['data'][$i]]);
            }
            fputcsv($out, []);

            fputcsv($out, ['DOANH THU THEO NGÀY']);
            fputcsv($out, ['Ngày', 'Doanh thu (VND)']);
            foreach ($data['revenueChart']['labels'] as $i => $dayLabel) {
                fputcsv($out, [$dayLabel, $data['revenueChart']['data'][$i]]);
            }
            fputcsv($out, []);

            fputcsv($out, ['TOP 10 SẢN PHẨM BÁN CHẠY']);
            fputcsv($out, ['STT', 'Sản phẩm', 'Số lượng bán', 'Doanh thu (VND)']);
            foreach ($data['topProducts'] as $i => $row) {
                fputcsv($out, [
                    $i + 1,
                    $row->product_name,
                    $row->sold_qty,
                    $row->sold_amount,
                ]);
            }
            fputcsv($out, []);

            fputcsv($out, ['DANH SÁCH ĐƠN HÀNG TRONG KỲ']);
            fputcsv($out, ['Mã đơn', 'Khách hàng', 'Email', 'Trạng thái', 'Tổng tiền', 'Ngày tạo']);
            foreach ($data['exportOrders'] as $order) {
                fputcsv($out, [
                    $order->order_code ?? '#'.$order->id,
                    $order->user?->name ?? 'N/A',
                    $order->user?->email ?? '',
                    $this->statusLabelVi($order->status),
                    $order->total_amount,
                    $order->created_at?->format('d/m/Y H:i'),
                ]);
            }

            fclose($out);
        }, $filename, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /** @return array{0:Carbon,1:Carbon,2:string} */
    private function resolveDateRange(Request $request): array
    {
        $range = $request->get('range', '30');
        $today = Carbon::today();

        return match ($range) {
            'today' => [$today->copy(), $today->copy(), 'today'],
            '7' => [$today->copy()->subDays(6), $today->copy(), '7'],
            'month' => [$today->copy()->startOfMonth(), $today->copy(), 'month'],
            'custom' => [
                Carbon::parse($request->get('from', $today->copy()->subDays(29)->format('Y-m-d')))->startOfDay(),
                Carbon::parse($request->get('to', $today->format('Y-m-d')))->startOfDay(),
                'custom',
            ],
            default => [$today->copy()->subDays(29), $today->copy(), '30'],
        };
    }

    private function buildReportData(Carbon $from, Carbon $to): array
    {
        $ordersQuery = Order::query()
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to);

        $orderCount = (clone $ordersQuery)->count();

        $revenue = (clone $ordersQuery)
            ->where('status', '!=', 'cancelled')
            ->sum('total_amount');

        $productCount = Product::count();

        $statusCounts = (clone $ordersQuery)
            ->select('status', DB::raw('COUNT(*) as total'))
            ->groupBy('status')
            ->pluck('total', 'status');

        $statusLabels = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
        $statusChart = [
            'labels' => array_map(fn ($s) => $this->statusLabelVi($s), $statusLabels),
            'data' => array_map(fn ($s) => (int) ($statusCounts[$s] ?? 0), $statusLabels),
        ];

        $revenueByDay = (clone $ordersQuery)
            ->where('status', '!=', 'cancelled')
            ->select(DB::raw('DATE(created_at) as day'), DB::raw('SUM(total_amount) as total'))
            ->groupBy(DB::raw('DATE(created_at)'))
            ->orderBy('day')
            ->get()
            ->keyBy('day');

        $revenueLabels = [];
        $revenueData = [];
        $cursor = $from->copy();
        while ($cursor->lte($to)) {
            $key = $cursor->format('Y-m-d');
            $revenueLabels[] = $cursor->format('d/m');
            $revenueData[] = (float) ($revenueByDay[$key]->total ?? 0);
            $cursor->addDay();
        }

        $topProducts = OrderItem::query()
            ->select(
                'order_items.product_id',
                'order_items.product_name',
                DB::raw('SUM(order_items.quantity) as sold_qty'),
                DB::raw('SUM(order_items.quantity * order_items.unit_price) as sold_amount')
            )
            ->join('orders', 'orders.id', '=', 'order_items.order_id')
            ->where('orders.status', '!=', 'cancelled')
            ->whereDate('orders.created_at', '>=', $from)
            ->whereDate('orders.created_at', '<=', $to)
            ->groupBy('order_items.product_id', 'order_items.product_name')
            ->orderByDesc('sold_qty')
            ->limit(10)
            ->get();

        $recentOrders = (clone $ordersQuery)->with('user')->latest()->take(8)->get();

        $exportOrders = (clone $ordersQuery)->with('user')->latest()->get();

        return [
            'stats' => [
                'revenue' => $revenue,
                'orders' => $orderCount,
                'products' => $productCount,
            ],
            'statusChart' => $statusChart,
            'revenueChart' => [
                'labels' => $revenueLabels,
                'data' => $revenueData,
            ],
            'topProducts' => $topProducts,
            'recentOrders' => $recentOrders,
            'exportOrders' => $exportOrders,
        ];
    }

    private function statusLabelVi(string $status): string
    {
        return match ($status) {
            'pending' => 'Chờ xác nhận',
            'processing' => 'Đang xử lý',
            'shipped' => 'Đang giao',
            'delivered' => 'Đã giao',
            'cancelled' => 'Đã hủy',
            default => $status,
        };
    }
}
