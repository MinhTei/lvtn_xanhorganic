<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;


class UpdateFlashSalePrice extends Command
{
    //Tên lệnh để chạy trong terminal
    protected $signature = 'products:update-flash-price {--hour= : Giả lập giờ cụ thể để demo (0-23)}';  
    //Mô tả hiển thị khi dùng lệnh php artisan list
    protected $description = 'Tự động giảm giá khi cận date và ẩn khi hết hạn';

    public function handle()
    {
        // Lấy giờ hiện tại, hoặc giờ giả lập nếu có truyền --hour (dùng để demo)
        $currentHour = $this->option('hour') !== null
            ? (int) $this->option('hour')
            : now()->hour;

        if ($this->option('hour') !== null) {
            $this->info("[DEMO MODE] Đang giả lập giờ: {$currentHour}h00");
        }

        // Xử lý sản phẩm Daily Cycle (thịt tươi, rau củ - reset theo giờ trong ngày)
        $this->processDailyCycle($currentHour);

        //tìm tất cả sản phẩm đang bán, có hạn sử dụng, và dùng chế độ tiêu chuẩn
        $products = Product::whereNotNull('expiry_date')
            ->where('is_active', 1)
            ->where(function($q) {
                $q->whereNull('pricing_mode')->orWhere('pricing_mode', 'standard');
            })
            ->get();
        $count = 0;
        foreach($products as $product){
            // Tính tròn ngày (bỏ qua khác biệt về giờ phút)
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
                // Hàng còn rất tươi ( > 50% tuổi thọ) -> Không giảm giá
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
                // 21:00 trở đi → Hết giờ giao hàng → Reset về giá gốc (chuẩn bị ngày mai)
                $product->update(['sale_price' => null]);
            } elseif ($currentHour >= 17) {
                // 17:00 → 20:59 → Sắp hết giờ giao hàng: Giảm 40%
                $product->update(['sale_price' => round($product->price * 0.60)]);
            } elseif ($currentHour >= 14) {
                // 14:00 → 16:59 → Xế chiều: Giảm 20%
                $product->update(['sale_price' => round($product->price * 0.80)]);
            } else {
                // 00:00 → 13:59 → Hàng tươi buổi sáng: Giá gốc
                $product->update(['sale_price' => null]);
            }
            $count++;
        }

        $this->info("[Daily Cycle] Giờ hiện tại: {$currentHour}h → Đã cập nhật {$count} sản phẩm");
    }
}
