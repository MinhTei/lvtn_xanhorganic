<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model

{
    use HasFactory;
    protected $fillable =[
        'user_id','order_code','subtotal','discount_amount','coupon_code',
        'shipping_fee','shipping_type','delivery_slot',
        'shipping_name','shipping_phone','shipping_address',
        'total_amount','status','note'
    ];

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function orderItems(){
        return $this->hasMany(OrderItem::class,'order_id');
    }
    public function productReviews(){
        return $this->hasMany(ProductReview::class,'order_id');
    }
    public function orderPayment(){
        return $this->hasOne(OrderPayment::class,'order_id');
    }
    public function orderStatusLogs(){
        return $this->hasMany(OrderStatusLogs::class,'order_id');
    }
    public function couponUsages(){
        return $this->hasMany(CouponUsage::class,'order_id');
    }

    public function getFinalAmountAttribute()
    {
        return $this->total_amount;
    }

    /**
     * Luồng trạng thái đơn hàng (chỉ được đi đúng bước kế tiếp).
     *
     * pending → processing → shipped → delivered
     * pending|processing → cancelled
     * delivered / cancelled = trạng thái cuối (không đổi tiếp)
     */
    public const STATUS_FLOW = [
        'pending' => ['processing', 'cancelled'],
        'processing' => ['shipped', 'cancelled'],
        'shipped' => ['delivered'],
        'delivered' => [],
        'cancelled' => [],
    ];

    public const STATUS_LABELS = [
        'pending' => 'Chờ xác nhận',
        'processing' => 'Đã xác nhận',
        'shipped' => 'Đang giao',
        'delivered' => 'Đã giao',
        'cancelled' => 'Đã hủy',
    ];

    /** Các trạng thái được phép chuyển tiếp từ trạng thái hiện tại */
    public function allowedNextStatuses(): array
    {
        return self::STATUS_FLOW[$this->status] ?? [];
    }

    /** Kiểm tra có được chuyển sang $newStatus không */
    public function canTransitionTo(string $newStatus): bool
    {
        return in_array($newStatus, $this->allowedNextStatuses(), true);
    }

    public function statusLabel(): string
    {
        return self::STATUS_LABELS[$this->status] ?? $this->status;
    }
}
