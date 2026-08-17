<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Collection;


class DeliveryService
{
    public const STANDARD_FEE = 25000;
    public const EXPRESS_FEE = 45000;
    public const FREE_SHIP_THRESHOLD = 500000;

    public const CUTOFF_HOUR = 20;

    /**
     * Khung giờ nhận hàng tươi sống.
     * start = giờ bắt đầu (khách chỉ chọn được nếu hiện tại < start khi giao hôm nay).
     *
     * @var array<string, array{label:string,start:int,end:int}>
     */
    public const FRESH_SLOTS = [
        '07-08' => ['label' => 'Từ 07h00 - 08h00', 'start' => 7, 'end' => 8],
        '08-09' => ['label' => 'Từ 08h00 - 09h00', 'start' => 8, 'end' => 9],
        '09-10' => ['label' => 'Từ 09h00 - 10h00', 'start' => 9, 'end' => 10],
        '10-11' => ['label' => 'Từ 10h00 - 11h00', 'start' => 10, 'end' => 11],
        '11-12' => ['label' => 'Từ 11h00 - 12h00', 'start' => 11, 'end' => 12],
        '12-13' => ['label' => 'Từ 12h00 - 13h00', 'start' => 12, 'end' => 13],
        '13-14' => ['label' => 'Từ 13h00 - 14h00', 'start' => 13, 'end' => 14],
        '14-15' => ['label' => 'Từ 14h00 - 15h00', 'start' => 14, 'end' => 15],
        '15-16' => ['label' => 'Từ 15h00 - 16h00', 'start' => 15, 'end' => 16],
        '16-17' => ['label' => 'Từ 16h00 - 17h00', 'start' => 16, 'end' => 17],
        '17-18' => ['label' => 'Từ 17h00 - 18h00', 'start' => 17, 'end' => 18],
        '18-19' => ['label' => 'Từ 18h00 - 19h00', 'start' => 18, 'end' => 19],
        '19-20' => ['label' => 'Từ 19h00 - 20h00', 'start' => 19, 'end' => 20],
        '20-21' => ['label' => 'Từ 20h00 - 21h00', 'start' => 20, 'end' => 21],
    ];

    public const LEGACY_SLOTS = [
        '08-12' => 'Sáng (08:00 - 12:00)',
        '13-17' => 'Chiều (13:00 - 17:00)',
        '18-21' => 'Tối (18:00 - 21:00)',
        'express-2h' => 'Giao nhanh trong 2 giờ',
    ];

    /**
     * @return array{
     *   allow_standard:bool,
     *   allow_express:bool,
     *   requires_slot:bool,
     *   has_fresh:bool,
     *   has_dry:bool,
     *   conflict:bool,
     *   message:?string,
     *   fresh_names:array,
     *   dry_names:array,
     *   express_only_names:array
     * }
     */
    public static function optionsForCart(Collection $cartItems): array
    {
        $freshNames = [];
        $dryNames = [];
        $expressOnly = [];
        $allowStandard = true;
        $allowExpress = true;

        foreach ($cartItems as $item) {
            $product = $item->product;
            if (!$product) {
                continue;
            }
            $mode = $product->delivery_mode ?? 'both';

            if ($mode === 'standard') {
                $dryNames[] = $product->name;
                $allowExpress = false;
            } elseif ($mode === 'express') {
                $expressOnly[] = $product->name;
                $allowStandard = false;
            } else {
                // both = hàng tươi sống
                $freshNames[] = $product->name;
            }
        }

        $conflict = !$allowStandard && !$allowExpress;
        $hasFresh = count($freshNames) > 0;
        $hasDry = count($dryNames) > 0;
        // Có hàng tươi → đặt theo khung giờ (kể cả khi kèm hàng khô)
        $requiresSlot = $hasFresh;

        $message = null;
        if ($conflict) {
            $message = 'Giỏ hàng có cả sản phẩm chỉ giao theo ngày (hàng khô) và chỉ giao nhanh 2 giờ. Vui lòng tách đơn.';
        } elseif ($hasFresh && $hasDry) {
            $message = 'Đơn có hàng tươi sống nên giao theo khung giờ nhận hàng (hàng khô sẽ giao cùng chuyến).';
        } elseif ($hasFresh) {
            $message = null;
        } elseif ($hasDry) {
            $message = 'Hàng khô / lưu kho: giao trong 3–5 ngày làm việc.';
        } elseif (count($expressOnly)) {
            $message = 'Sản phẩm này chỉ hỗ trợ giao nhanh trong 2 giờ.';
        }

        return [
            'allow_standard' => $allowStandard && !$conflict,
            'allow_express' => $allowExpress && !$conflict,
            'requires_slot' => $requiresSlot && !$conflict,
            'has_fresh' => $hasFresh,
            'has_dry' => $hasDry,
            'conflict' => $conflict,
            'message' => $message,
            'fresh_names' => array_values(array_unique($freshNames)),
            'dry_names' => array_values(array_unique($dryNames)),
            'express_only_names' => array_values(array_unique($expressOnly)),
        ];
    }

    /**
     * Nhóm khung giờ theo ngày để UI chọn: Hôm nay | Ngày mai.
     *
     * @return array{
     *   today: array{available:bool,date:string,slots:array<string,string>},
     *   tomorrow: array{available:bool,date:string,slots:array<string,string>},
     *   cutoff_hour:int
     * }
     */
    public static function groupedFreshSlots(?Carbon $now = null): array
    {
        $now = $now ? $now->copy() : now();
        $today = $now->copy()->startOfDay();
        $tomorrow = $today->copy()->addDay();

        $todaySlots = [];
        if ($now->hour < self::CUTOFF_HOUR) {
            foreach (self::FRESH_SLOTS as $key => $meta) {
                if ($now->hour < $meta['start']) {
                    $code = $today->format('Y-m-d').'|'.$key;
                    $todaySlots[$code] = $meta['label'];
                }
            }
        }

        $tomorrowSlots = [];
        foreach (self::FRESH_SLOTS as $key => $meta) {
            $code = $tomorrow->format('Y-m-d').'|'.$key;
            $tomorrowSlots[$code] = $meta['label'];
        }

        return [
            'today' => [
                'available' => count($todaySlots) > 0,
                'date' => $today->format('Y-m-d'),
                'slots' => $todaySlots,
            ],
            'tomorrow' => [
                'available' => true,
                'date' => $tomorrow->format('Y-m-d'),
                'slots' => $tomorrowSlots,
            ],
            'cutoff_hour' => self::CUTOFF_HOUR,
        ];
    }

    /**
     * Danh sách khung giờ khả dụng tại thời điểm đặt (cutoff 20h).
     *
     * @return array<string,string> key = "Y-m-d|sang" => label
     */
    public static function availableFreshSlots(?Carbon $now = null): array
    {
        $grouped = self::groupedFreshSlots($now);

        return $grouped['today']['slots'] + $grouped['tomorrow']['slots'];
    }

    public static function isValidFreshSlot(?string $slot, ?Carbon $now = null): bool
    {
        if (!$slot) {
            return false;
        }

        return array_key_exists($slot, self::availableFreshSlots($now));
    }

    public static function labelForSlot(?string $slot): string
    {
        if (!$slot) {
            return '';
        }

        if (isset(self::LEGACY_SLOTS[$slot])) {
            return self::LEGACY_SLOTS[$slot];
        }

        if (!str_contains($slot, '|')) {
            return $slot;
        }

        [$date, $key] = explode('|', $slot, 2);
        $meta = self::FRESH_SLOTS[$key] ?? null;
        if (!$meta) {
            return $slot;
        }

        try {
            $d = Carbon::parse($date);
            $dayLabel = $d->isToday() ? 'Hôm nay' : ($d->isTomorrow() ? 'Ngày mai' : $d->format('d/m/Y'));
        } catch (\Throwable) {
            $dayLabel = $date;
        }

        return $dayLabel.' · '.$meta['label'];
    }

    public static function shippingFee(string $shippingType, float $subtotal): float
    {
        if ($shippingType === 'express') {
            return self::EXPRESS_FEE;
        }

        return $subtotal >= self::FREE_SHIP_THRESHOLD ? 0 : self::STANDARD_FEE;
    }

    public static function defaultType(array $options): ?string
    {
        if ($options['allow_standard']) {
            return 'standard';
        }
        if ($options['allow_express']) {
            return 'express';
        }

        return null;
    }
}
