<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderPayment extends Model
{
    protected $fillable=['order_id','payment_method','transaction_id','amount','payment_status','paid_at'];

    public function order(){
        return $this->belongsTo(Order::class, 'order_id');
    }
}
