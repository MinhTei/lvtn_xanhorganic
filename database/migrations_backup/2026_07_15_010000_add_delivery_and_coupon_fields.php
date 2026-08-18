<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * - products.delivery_mode: loại giao hàng SP hỗ trợ (standard / express / both)
 * - orders: lưu phương thức giao + khung giờ + mã coupon đã dùng
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('delivery_mode', 20)->default('both')->after('is_active');
            // standard = chỉ giao thường | express = chỉ giao nhanh | both = cả hai
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('shipping_type', 20)->default('standard')->after('shipping_fee');
            $table->string('delivery_slot', 50)->nullable()->after('shipping_type');
            $table->string('coupon_code', 50)->nullable()->after('discount_amount');
        });
    }

    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn('delivery_mode');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['shipping_type', 'delivery_slot', 'coupon_code']);
        });
    }
};
