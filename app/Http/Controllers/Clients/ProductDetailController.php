<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Product;

class ProductDetailController extends Controller
{
    public function showProductDetail($slug)
    {
        $product = Product::with('images', 'category', 'productReviews.user')
            ->where('slug', $slug)
            ->where('is_active', 1)
            ->firstOrFail();

        $reviews       = $product->productReviews()->with('user')->latest()->get();
        $reviewCount   = $reviews->count();
        $averageRating = $reviewCount > 0 ? $reviews->avg('rating') : 0;
        $fullStars     = (int) floor($averageRating);
        $displayPrice    = ($product->sale_price && $product->sale_price < $product->price)
                            ? $product->sale_price
                            : $product->price;

        $relatedProducts = Product::with('images')
            ->where('is_active', 1)
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->limit(4)->get();

        return view('clients.pages.product_detail', compact(
            'product', 'displayPrice', 'fullStars',
            'reviewCount', 'averageRating', 'reviews',
            'relatedProducts'
        ));
    }
}
