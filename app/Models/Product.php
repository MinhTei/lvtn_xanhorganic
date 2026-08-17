<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'category_id', 'name', 'slug', 'description', 'price', 'sale_price',
        'quantity', 'unit', 'is_featured', 'is_active', 'delivery_mode', 'manufacture_date', 'expiry_date',
    ];

    protected $casts = [
        'manufacture_date' => 'date',
        'expiry_date' => 'date',
    ];
    /** SP có hỗ trợ giao thường không */
    public function supportsStandardDelivery(): bool
    {
        return in_array($this->delivery_mode ?? 'both', ['standard', 'both'], true);
    }

    /** SP có hỗ trợ giao nhanh không */
    public function supportsExpressDelivery(): bool
    {
        return in_array($this->delivery_mode ?? 'both', ['express', 'both'], true);
    }

    public function wishlists()
    {
        return $this->hasMany(Wishlist::class, 'product_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function carts()
    {
        return $this->hasMany(Cart::class, 'product_id');
    }
    // public function productImages()
    // {
    //     return $this->hasMany(ProductImage::class, 'product_id');
    // }
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }
    public function productReviews()
    {
        return $this->hasMany(ProductReview::class, 'product_id');
    }

    /**
     * Accessor: lấy URL ảnh primary (is_primary = 1).
     * Dùng trong View: $product->image_url
     */
    public function getImageUrlAttribute(): string
    {
        $img = $this->images->where('is_primary', 1)->first()
            ?: $this->images->first();

        if ($img) {
            return $img->display_url;
        }

        return asset('assets/clients/img/product/' . $this->slug . '.jpg');
    }
    public function getLabelPriceSale():int{
        if($this->sale_price && $this->price > 0 && $this->sale_price < $this->price){
            return (int) round((($this->price-$this->sale_price)/$this->price) * 100);
        }
        return 0;

    }

    public function recipes()
    {
        return $this->belongsToMany(Recipe::class, 'product_recipe', 'product_id', 'recipe_id');
    }
}
