<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model
{
    protected $fillable=['order_id','product_id','product_name','product_image','quantity','unit_price'];

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }
    public function order(){
        return $this->belongsTo(Order::class,'order_id');
    }

    public function getSubtotalAttribute()
    {
        return $this->unit_price * $this->quantity;
    }
}
