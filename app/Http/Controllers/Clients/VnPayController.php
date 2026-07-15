<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderStatusLogs;
use App\Services\ClientCart;
use App\Services\VnPayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class VnPayController extends Controller
{
    /**
     * Return URL — VNPay chuyển khách về sau khi thanh toán.
     */
    public function return(Request $request)
    {
        $verified = VnPayService::verifyCallback($request);

        if (!$verified['txn_ref']) {
            return redirect()->route('home')
                ->with('error', 'Phản hồi VNPay không hợp lệ.');
        }

        $order = Order::with('orderPayment')
            ->where('order_code', $verified['txn_ref'])
            ->first();

        if (!$order) {
            return redirect()->route('home')
                ->with('error', 'Không tìm thấy đơn hàng '.$verified['txn_ref']);
        }

        // Chỉ chủ đơn mới xem (nếu đang login)
        if (Auth::check() && $order->user_id !== Auth::id()) {
            abort(403);
        }

        if (!$verified['valid']) {
            Log::warning('VNPay return hash invalid', ['order' => $order->order_code, 'data' => $verified['data']]);

            return redirect()
                ->route('checkout.success', $order)
                ->with('warning', 'Chữ ký VNPay không hợp lệ. Đơn vẫn được giữ, vui lòng liên hệ hỗ trợ.');
        }

        $this->applyPaymentResult($order, $verified);
        $order->refresh()->load(['orderItems', 'orderPayment', 'user']);

        if (VnPayService::isSuccess($verified)) {
            return redirect()
                ->route('checkout.success', $order)
                ->with('success', 'Thanh toán VNPay thành công!');
        }

        $msg = $this->messageForCode($verified['response_code'] ?? '');

        return redirect()
            ->route('checkout.success', $order)
            ->with('warning', 'Thanh toán VNPay không thành công: '.$msg.'. Đơn đã hủy — sản phẩm đã được trả lại giỏ hàng.')
            ->with('payment_failed', true);
    }

    /**
     * IPN — VNPay gọi server-to-server (không CSRF).
     */
    public function ipn(Request $request)
    {
        $verified = VnPayService::verifyCallback($request);

        if (!$verified['valid']) {
            return response()->json(['RspCode' => '97', 'Message' => 'Invalid signature']);
        }

        $order = Order::with('orderPayment')
            ->where('order_code', $verified['txn_ref'])
            ->first();

        if (!$order) {
            return response()->json(['RspCode' => '01', 'Message' => 'Order not found']);
        }

        $payment = $order->orderPayment;
        if ($payment && $payment->payment_status === 'completed') {
            return response()->json(['RspCode' => '02', 'Message' => 'Order already confirmed']);
        }

        // Kiểm tra số tiền
        if ($verified['amount'] !== null
            && abs($verified['amount'] - (float) $order->total_amount) > 0.01) {
            return response()->json(['RspCode' => '04', 'Message' => 'Invalid amount']);
        }

        $this->applyPaymentResult($order, $verified);

        if (VnPayService::isSuccess($verified)) {
            return response()->json(['RspCode' => '00', 'Message' => 'Confirm Success']);
        }

        return response()->json(['RspCode' => '00', 'Message' => 'Confirm Success']);
    }

    private function applyPaymentResult(Order $order, array $verified): void
    {
        DB::transaction(function () use ($order, $verified) {
            $payment = $order->orderPayment()->lockForUpdate()->first();
            if (!$payment) {
                return;
            }

            // Đã completed rồi thì không ghi đè
            if ($payment->payment_status === 'completed') {
                return;
            }

            $success = VnPayService::isSuccess($verified);

            $payment->update([
                'payment_method' => 'VNPay',
                'transaction_id' => $verified['transaction_no'] ?: $payment->transaction_id,
                'payment_status' => $success ? 'completed' : 'failed',
                'paid_at' => $success ? now() : null,
            ]);

            if ($success) {
                OrderStatusLogs::create([
                    'order_id' => $order->id,
                    'old_status' => $order->status,
                    'new_status' => $order->status,
                    'note' => 'Thanh toán VNPay thành công. Mã GD: '.($verified['transaction_no'] ?? '—'),
                ]);

                return;
            }

            // Hủy / thất bại VNPay → hủy đơn + hoàn kho + trả SP về giỏ
            $reason = 'VNPay thất bại / hủy. Mã: '.($verified['response_code'] ?? '—');

            if ($order->canTransitionTo('cancelled')) {
                $order->load(['orderItems.product', 'user']);
                foreach ($order->orderItems as $item) {
                    if ($item->product) {
                        $item->product->increment('quantity', $item->quantity);
                    }
                }

                $oldStatus = $order->status;
                $order->update(['status' => 'cancelled']);

                OrderStatusLogs::create([
                    'order_id' => $order->id,
                    'old_status' => $oldStatus,
                    'new_status' => 'cancelled',
                    'note' => $reason,
                ]);

                // Đưa lại vào giỏ để khách đặt lại dễ dàng
                ClientCart::restoreFromOrder($order->fresh(['orderItems', 'user']));
            } else {
                OrderStatusLogs::create([
                    'order_id' => $order->id,
                    'old_status' => $order->status,
                    'new_status' => $order->status,
                    'note' => $reason,
                ]);
            }
        });
    }

    private function messageForCode(string $code): string
    {
        return match ($code) {
            '00' => 'Thành công',
            '07' => 'Trừ tiền thành công, giao dịch bị nghi ngờ',
            '09' => 'Thẻ/Tài khoản chưa đăng ký InternetBanking',
            '10' => 'Xác thực thông tin thẻ/tài khoản không đúng quá 3 lần',
            '11' => 'Hết hạn chờ thanh toán',
            '12' => 'Thẻ/Tài khoản bị khóa',
            '13' => 'Sai OTP',
            '24' => 'Khách hủy giao dịch',
            '51' => 'Tài khoản không đủ số dư',
            '65' => 'Vượt hạn mức giao dịch trong ngày',
            '75' => 'Ngân hàng thanh toán đang bảo trì',
            '79' => 'Nhập sai mật khẩu thanh toán quá số lần quy định',
            '97' => 'Chữ ký không hợp lệ',
            default => 'Mã lỗi '.$code,
        };
    }
}
