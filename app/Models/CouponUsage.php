<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CouponUsage extends Model
{
    protected $fillable=['coupon_id','user_id','order_id','discount_amount'];

    public function coupon(){
        return $this->belongsTo(Coupon::class,'coupon_id');
    }

    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
    public function order(){
        return $this->belongsTo(Order::class,'order_id');
    }
    
}
