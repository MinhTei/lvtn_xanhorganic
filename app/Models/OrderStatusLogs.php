<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatusLogs extends Model
{
    protected $fillable=['order_id','old_status','new_status','note'];

    public function order(){
        return $this->belongsTo(Order::class,'order_id');
    }
}
