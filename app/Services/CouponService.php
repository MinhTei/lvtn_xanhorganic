<?php

namespace App\Services;

use App\Models\Coupon;
use App\Models\CouponUsage;
use Carbon\Carbon;


class CouponService
{
    /**
     * @return array{success:bool,message:string,discount?:float,coupon?:Coupon}
     */
    public static function validate(string $code, float $subtotal, ?int $userId = null): array
    {
        $code = strtoupper(trim($code));
        if ($code === '') {
            return ['success' => false, 'message' => 'Vui lòng nhập mã giảm giá.'];
        }

        $coupon = Coupon::where('code', $code)->first();
        if (!$coupon) {
            return ['success' => false, 'message' => 'Mã giảm giá không tồn tại.'];
        }

        $today = Carbon::today();
        $start = Carbon::parse($coupon->start_date)->startOfDay();
        $end = Carbon::parse($coupon->end_date)->endOfDay();

        if ($today->lt($start) || $today->gt($end)) {
            return ['success' => false, 'message' => 'Mã giảm giá đã hết hạn hoặc chưa đến ngày áp dụng.'];
        }

        if ($subtotal < (float) $coupon->min_order_value) {
            return [
                'success' => false,
                'message' => 'Đơn tối thiểu để dùng mã: ' . number_format($coupon->min_order_value, 0, ',', '.') . '₫',
            ];
        }

        if ($coupon->usage_limit !== null && $coupon->usages()->count() >= $coupon->usage_limit) {
            return ['success' => false, 'message' => 'Mã giảm giá đã hết lượt sử dụng trên hệ thống.'];
        }

        // Mỗi user chỉ dùng giới hạn lượt (mặc định 1)
        if ($userId) {
            $userUsages = CouponUsage::where('coupon_id', $coupon->id)->where('user_id', $userId)->count();
            if ($userUsages >= ($coupon->usage_limit_per_user ?? 1)) {
                return ['success' => false, 'message' => 'Bạn đã hết lượt sử dụng mã này.'];
            }
        }

        $discount = self::calcDiscount($coupon, $subtotal);

        return [
            'success' => true,
            'message' => 'Áp dụng mã thành công. Giảm ' . number_format($discount, 0, ',', '.') . '₫',
            'discount' => $discount,
            'coupon' => $coupon,
        ];
    }

    public static function calcDiscount(Coupon $coupon, float $subtotal): float
    {
        if ($coupon->discount_type === 'percentage') {
            return round($subtotal * ((float) $coupon->discount_value / 100), 0);
        }

        return min((float) $coupon->discount_value, $subtotal);
    }
}
