<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable=['code','created_by','discount_type','discount_value','min_order_value','start_date','end_date', 'usage_limit', 'usage_limit_per_user'];

    public function user(){
        return $this->belongsTo(User::class,'created_by');
    }
    public function createdBy(){
        return $this->belongsTo(User::class,'created_by');
    }
    public function usages(){
        return $this->hasMany(CouponUsage::class,'coupon_id');
    }
}
