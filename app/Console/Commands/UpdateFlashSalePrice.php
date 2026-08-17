<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;


class UpdateFlashSalePrice extends Command
{
    //Tên lệnh để chạy trong terminal
    protected $signature = 'products:update-flash-price';
    //Mô tả hiển thị khi dùng lệnh php artisan list
    protected $description = 'Tự động giảm giá khi cận date và ẩn khi hết hạn';

    public function handle()
    {
        //tìm tất cả sản phẩm  đang bán và có cài đặt ngày hết hạn
        $products = Product::whereNotNull('expiry_date')->where('is_active',1)->get();
        $count =0;
        foreach($products as $product){
            //tính ngày còn lại
            $dayLeft = now()->diffInDays($product->expiry_date,false);
            if($dayLeft <= 0){
                $product->update(['is_active'=>0,'sale_price'=>null]);
                $count ++;
                
            }elseif ($dayLeft <=1){// 60% off 
                $product->update(['sale_price'=>round($product->price*0.40)]);
                $count++;
            }elseif ($dayLeft <= 3){ //40% off
                $product->update(['sale_price'=>round($product->price*0.60)]);
                $count++;
            }elseif ($dayLeft <= 7){ //20% off
                $product->update(['sale_price'=>round($product->price*0.80)]);
                $count++;
            }else {
                // > 7 ngày thì không giảm giá
                if($product->sale_price != null) {
                    $product->update(['sale_price'=>null]);
                    $count++;
                }
            }
        }
        $this->info("Đã kiểm tra và cập nhật {$count} sản phẩm");
    }
    
}
