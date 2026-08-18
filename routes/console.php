<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

//Hẹn giờ chạy lệnh flash sale (Mỗi giờ 1 lần để cập nhật theo khung giờ)
Schedule::command('products:update-flash-price')->hourly();