<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\OrderStatusUpdatedMail;
use App\Models\Order;
use App\Models\OrderStatusLogs;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;

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

    public function invoice(Order $order)
    {
        $order->load(['user', 'orderItems', 'orderPayment']);

        $pdf = Pdf::loadView('admin.orders.invoice', [
            'order' => $order,
            'statusLabels' => Order::STATUS_LABELS,
        ])->setPaper('a4');

        return $pdf->stream('hoa-don-' . $order->order_code . '.pdf');
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

        // Xác nhận đơn: kiểm tra tồn kho thực tế trước khi chuyển trạng thái
        if ($data['status'] === 'processing') {
            $this->assertStockEnoughToConfirm($order);
        }

        $oldStatus = $order->status;
        $note = null;

        try {
            DB::transaction(function () use ($order, $data, &$note) {
                $old = $order->status;

                if ($data['status'] === 'cancelled') {
                    $order->load('orderItems.product');
                    foreach ($order->orderItems as $item) {
                        if ($item->product) {
                            $item->product->increment('quantity', $item->quantity);
                        }
                    }
                }

                $order->update(['status' => $data['status']]);

                if ($data['status'] === 'delivered') {
                    $payment = $order->orderPayment()->first();
                    if (
                        $payment
                        && $payment->payment_method === 'COD'
                        && $payment->payment_status !== 'completed'
                    ) {
                        $payment->update([
                            'payment_status' => 'completed',
                            'paid_at' => now(),
                        ]);
                    }
                }

                $note = $data['note'] ?? ('Admin đổi trạng thái: ' . auth()->user()->name);
                if ($data['status'] === 'processing' && empty($data['note'])) {
                    $note = 'Đã kiểm tra tồn kho và xác nhận đơn — ' . auth()->user()->name;
                }

                OrderStatusLogs::create([
                    'order_id' => $order->id,
                    'old_status' => $old,
                    'new_status' => $data['status'],
                    'note' => $note,
                ]);
            });
        } catch (ValidationException $e) {
            return back()->with('error', collect($e->errors())->flatten()->implode(' '));
        }

        $order->refresh()->loadMissing('user');
        try {
            $email = $order->shipping_email ?: $order->user?->email;
            if ($email) {
                Mail::to($email)->send(
                    new OrderStatusUpdatedMail($order, $oldStatus, $data['status'], $note)
                );
            }
        } catch (\Throwable $e) {
            report($e);
        }

        return back()->with('success', 'Đã cập nhật trạng thái đơn hàng.');
    }


    private function assertStockEnoughToConfirm(Order $order): void
    {
        $order->load('orderItems.product');
        $errors = [];

        foreach ($order->orderItems as $item) {
            $product = $item->product;

            if (!$product) {
                $errors[] = "Sản phẩm \"{$item->product_name}\" không còn trong hệ thống.";
                continue;
            }

            // if (isset($product->is_active) && !$product->is_active) {
            //     $errors[] = "Sản phẩm \"{$product->name}\" đang ngừng kinh doanh.";
            // }

            $available = (int) $product->quantity + (int) $item->quantity;
            if ($available < (int) $item->quantity) {
                $errors[] = "Sản phẩm \"{$product->name}\" không đủ hàng (cần {$item->quantity}, khả dụng {$available}).";
            }
        }

        if ($errors) {
            throw ValidationException::withMessages([
                'status' => $errors,
            ]);
        }
    }
}
