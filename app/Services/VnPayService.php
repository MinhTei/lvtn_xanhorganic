<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * VNPay — bám sát code mẫu PHP chính thức (HMAC SHA512).
 *
 * @see https://sandbox.vnpayment.vn/apis/docs/huong-dan-tich-hop/
 */
class VnPayService
{
    public static function createPaymentUrl(Order $order, string $ipAddr): string
    {
        $vnp_TmnCode = config('vnpay.tmn_code');
        $vnp_HashSecret = config('vnpay.hash_secret');
        $vnp_Url = rtrim((string) config('vnpay.url'), '?');
        $vnp_Returnurl = config('vnpay.return_url') ?: url('/vnpay/return');

        $vnp_TxnRef = $order->order_code;
        $vnp_OrderInfo = 'Thanh toan don hang '.$vnp_TxnRef;
        $vnp_OrderType = 'billpayment';
        $vnp_Amount = (int) round((float) $order->total_amount * 100);
        $vnp_Locale = 'vn';
        $vnp_IpAddr = self::normalizeIp($ipAddr);
        $vnp_CreateDate = now('Asia/Ho_Chi_Minh')->format('YmdHis');

        $inputData = [
            'vnp_Version' => '2.1.0',
            'vnp_TmnCode' => $vnp_TmnCode,
            'vnp_Amount' => $vnp_Amount,
            'vnp_Command' => 'pay',
            'vnp_CreateDate' => $vnp_CreateDate,
            'vnp_CurrCode' => 'VND',
            'vnp_IpAddr' => $vnp_IpAddr,
            'vnp_Locale' => $vnp_Locale,
            'vnp_OrderInfo' => $vnp_OrderInfo,
            'vnp_OrderType' => $vnp_OrderType,
            'vnp_ReturnUrl' => $vnp_Returnurl,
            'vnp_TxnRef' => $vnp_TxnRef,
        ];

        ksort($inputData);

        $query = '';
        $hashdata = '';
        $i = 0;
        foreach ($inputData as $key => $value) {
            if ($i === 1) {
                $hashdata .= '&'.urlencode($key).'='.urlencode((string) $value);
            } else {
                $hashdata .= urlencode($key).'='.urlencode((string) $value);
                $i = 1;
            }
            $query .= urlencode($key).'='.urlencode((string) $value).'&';
        }

        $vnpSecureHash = hash_hmac('sha512', $hashdata, $vnp_HashSecret);
        $paymentUrl = $vnp_Url.'?'.$query.'vnp_SecureHash='.$vnpSecureHash;

        Log::info('VNPay create payment', [
            'txn' => $vnp_TxnRef,
            'amount' => $vnp_Amount,
            'ip' => $vnp_IpAddr,
            'return' => $vnp_Returnurl,
            'tmn' => $vnp_TmnCode,
        ]);

        return $paymentUrl;
    }

    /**
     * @return array{valid:bool, data:array, response_code:?string, txn_ref:?string, transaction_no:?string, amount:?float}
     */
    public static function verifyCallback(Request $request): array
    {
        $inputData = [];
        foreach ($request->query() as $key => $value) {
            if (substr($key, 0, 4) === 'vnp_') {
                $inputData[$key] = $value;
            }
        }
        if ($inputData === []) {
            foreach ($request->all() as $key => $value) {
                if (is_string($key) && substr($key, 0, 4) === 'vnp_') {
                    $inputData[$key] = $value;
                }
            }
        }

        $vnp_SecureHash = $inputData['vnp_SecureHash'] ?? '';
        unset($inputData['vnp_SecureHash'], $inputData['vnp_SecureHashType']);
        ksort($inputData);

        $i = 0;
        $hashData = '';
        foreach ($inputData as $key => $value) {
            if ($i === 1) {
                $hashData .= '&'.urlencode($key).'='.urlencode((string) $value);
            } else {
                $hashData .= urlencode($key).'='.urlencode((string) $value);
                $i = 1;
            }
        }

        $secureHash = hash_hmac('sha512', $hashData, config('vnpay.hash_secret'));

        return [
            'valid' => hash_equals($secureHash, (string) $vnp_SecureHash),
            'data' => $inputData,
            'response_code' => $inputData['vnp_ResponseCode'] ?? null,
            'txn_ref' => $inputData['vnp_TxnRef'] ?? null,
            'transaction_no' => $inputData['vnp_TransactionNo'] ?? null,
            'amount' => isset($inputData['vnp_Amount']) ? ((float) $inputData['vnp_Amount'] / 100) : null,
        ];
    }

    public static function isSuccess(array $verified): bool
    {
        return $verified['valid'] && ($verified['response_code'] ?? '') === '00';
    }

    private static function normalizeIp(string $ip): string
    {
        $ip = trim($ip);
        if ($ip === '' || $ip === '::1' || str_starts_with($ip, 'fc') || str_starts_with($ip, 'fe80')) {
            return '127.0.0.1';
        }
        // IPv6 → fallback
        if (str_contains($ip, ':')) {
            return '127.0.0.1';
        }

        return $ip;
    }
}
