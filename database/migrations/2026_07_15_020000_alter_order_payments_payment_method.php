<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Cho phép lưu COD / bank_transfer / VNPay (không còn enum cứng COD|VNPay).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('order_payments')) {
            return;
        }

        // MySQL: đổi enum → string
        DB::statement("ALTER TABLE order_payments MODIFY payment_method VARCHAR(30) NOT NULL DEFAULT 'COD'");
    }

    public function down(): void
    {
        if (!Schema::hasTable('order_payments')) {
            return;
        }

        DB::statement("ALTER TABLE order_payments MODIFY payment_method ENUM('COD','VNPay') NOT NULL");
    }
};
