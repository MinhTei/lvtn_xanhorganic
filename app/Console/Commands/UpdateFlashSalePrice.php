<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;


class UpdateFlashSalePrice extends Command
{
    protected $signature = 'products:update-flash-price {--hour= : Giả lập giờ cụ thể để demo (0-23)}';  
    protected $description = 'Tự động giảm giá khi cận date và ẩn khi hết hạn';

    public function handle()
    {
        // Lấy giờ hiện tại, hoặc giờ giả lập nếu có truyền --hour 
        $currentHour = $this->option('hour') !== null
            ? (int) $this->option('hour')
            : now()->hour;

        if ($this->option('hour') !== null) {
            $this->info("[DEMO MODE] Đang giả lập giờ: {$currentHour}h00");
        }

     
        $this->processDailyCycle($currentHour);

        //tìm tất cả sản phẩm đang bán
        $products = Product::whereNotNull('expiry_date')
            ->where('is_active', 1)
            ->where(function($q) {
                $q->whereNull('pricing_mode')->orWhere('pricing_mode', 'standard');
            })
            ->get();
        $count = 0;
        foreach($products as $product){
            // Tính tròn ngày
            $dayLeft = now()->startOfDay()->diffInDays(\Carbon\Carbon::parse($product->expiry_date)->startOfDay(), false);
            
            // Tính tổng số ngày tuổi thọ của sản phẩm
            if ($product->manufacture_date) {
                $totalShelfLife = \Carbon\Carbon::parse($product->manufacture_date)->startOfDay()->diffInDays(\Carbon\Carbon::parse($product->expiry_date)->startOfDay(), true);
            } else {
                $totalShelfLife = $product->category->shelf_days ?? 7;
            }

            // Đảm bảo không bị lỗi chia cho 0
            if ($totalShelfLife <= 0) {
                $totalShelfLife = 1;
            }

            // Tính tỷ lệ phần trăm tuổi thọ còn lại
            $ratio = $dayLeft / $totalShelfLife;

            if($dayLeft < 0){
                // Đã quá hạn -> Ẩn
                $product->update(['is_active'=>0,'sale_price'=>null]);
                $count ++;
            }elseif ($dayLeft <= 1){
                // Ngày cuối cùng -> Giảm 60%
                $product->update(['sale_price'=>round($product->price*0.40)]);
                $count++;
            }elseif ($ratio <= 0.25){ 
                //  <= 25%  -> Giảm 40%
                $product->update(['sale_price'=>round($product->price*0.60)]);
                $count++;
            }elseif ($ratio <= 0.50){ 
                //  <= 50%  -> Giảm 20%
                $product->update(['sale_price'=>round($product->price*0.80)]);
                $count++;
            }else {
                //  > 50% 
                if($product->sale_price != null) {
                    $product->update(['sale_price'=>null]);
                    $count++;
                }
            }
        }
        $this->info("[Tiêu Chuẩn] Đã kiểm tra và cập nhật {$count} sản phẩm");
    }


    private function processDailyCycle(int $currentHour): void
    {
        $products = Product::where('is_active', 1)
            ->where('pricing_mode', 'daily_cycle')
            ->get();

        $count = 0;
        foreach ($products as $product) {
            if ($currentHour >= 21) {
                // 21h
                $product->update(['sale_price' => null]);
            } elseif ($currentHour >= 17) {
                // 17h → 21h
                $product->update(['sale_price' => round($product->price * 0.60)]);
            } elseif ($currentHour >= 14) {
                // 14h → 17h
                $product->update(['sale_price' => round($product->price * 0.80)]);
            } else {
                // 0h → 14h
                $product->update(['sale_price' => null]);
            }
            $count++;
        }

        $this->info("[Daily Cycle] Giờ hiện tại: {$currentHour}h → Đã cập nhật {$count} sản phẩm");
    }
}
