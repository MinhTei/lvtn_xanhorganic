<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusLogs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

/**
 * Quản lý đơn hàng: xem danh sách, chi tiết, đổi trạng thái.
 *
 * Ràng buộc luồng (Order::STATUS_FLOW):
 *   pending → processing → shipped → delivered
 *   pending / processing → cancelled
 *   Không được nhảy bước hoặc quay ngược.
 */
class AdminOrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with(['user', 'orderPayment'])->latest();

        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('order_code', 'like', "%{$search}%")
                    ->orWhere('shipping_name', 'like', "%{$search}%")
                    ->orWhere('shipping_phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(15)->withQueryString();

        return view('admin.orders.index', [
            'orders' => $orders,
            'statuses' => array_keys(Order::STATUS_LABELS),
            'statusLabels' => Order::STATUS_LABELS,
        ]);
    }

    public function show(Order $order)
    {
        $order->load(['user', 'orderItems.product', 'orderPayment', 'orderStatusLogs']);

        return view('admin.orders.show', [
            'order' => $order,
            'nextStatuses' => $order->allowedNextStatuses(),
            'statusLabels' => Order::STATUS_LABELS,
        ]);
    }

    public function updateStatus(Request $request, Order $order)
    {
        $allowed = $order->allowedNextStatuses();

        if (empty($allowed)) {
            return back()->with('error', 'Đơn hàng đã ở trạng thái cuối, không thể thay đổi thêm.');
        }

        $data = $request->validate([
            'status' => ['required', Rule::in($allowed)],
            'note' => 'nullable|string|max:500',
        ], [
            'status.in' => 'Trạng thái không hợp lệ. Đơn phải đi đúng trình tự: '
                . implode(' → ', array_map(
                    fn ($s) => Order::STATUS_LABELS[$s] ?? $s,
                    $allowed
                )),
        ]);

        if (!$order->canTransitionTo($data['status'])) {
            return back()->with(
                'error',
                'Không thể chuyển từ "' . $order->statusLabel() . '" sang "'
                . (Order::STATUS_LABELS[$data['status']] ?? $data['status']) . '".'
            );
        }

        DB::transaction(function () use ($order, $data) {
            $old = $order->status;

            // Hủy đơn → hoàn tồn kho
            if ($data['status'] === 'cancelled') {
                $order->load('orderItems.product');
                foreach ($order->orderItems as $item) {
                    if ($item->product) {
                        $item->product->increment('quantity', $item->quantity);
                    }
                }
            }

            $order->update(['status' => $data['status']]);

            OrderStatusLogs::create([
                'order_id' => $order->id,
                'old_status' => $old,
                'new_status' => $data['status'],
                'note' => $data['note'] ?: ('Admin đổi trạng thái: ' . auth()->user()->name),
            ]);
        });

        return back()->with('success', 'Đã cập nhật trạng thái đơn hàng.');
    }
}
