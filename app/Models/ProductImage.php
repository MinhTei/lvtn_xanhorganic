<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ProductImage extends Model
{
    protected $fillable = ['product_id', 'image_url', 'is_primary'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /** URL dùng trên <img src> (hỗ trợ /storage/... và URL đầy đủ cũ) */
    public function getDisplayUrlAttribute(): string
    {
        $url = (string) $this->image_url;
        if ($url === '') {
            return asset('assets/clients/img/product/product-1.jpg');
        }
        if (str_starts_with($url, 'http://') || str_starts_with($url, 'https://')) {
            return $url;
        }
        if (str_starts_with($url, '/')) {
            return url($url);
        }

        return asset($url);
    }

    public function deleteFileIfStored(): void
    {
        $url = (string) $this->image_url;
        // Chỉ xóa file upload trong storage/public
        if (preg_match('#^/storage/(.+)$#', $url, $m)) {
            Storage::disk('public')->delete($m[1]);
        }
    }
}
