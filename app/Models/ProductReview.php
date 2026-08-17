<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductReview extends Model
{
    protected $fillable=['user_id','product_id','order_id','rating','comment','is_visible'];

    public function product(){
        return $this->belongsTo(Product::class,'product_id');
    }

    public function order(){
        return $this->belongsTo(Order::class,'order_id');
    }
    public function user(){
        return $this->belongsTo(User::class,'user_id');
    }
}
