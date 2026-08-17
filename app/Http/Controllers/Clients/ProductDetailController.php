<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductDetailController extends Controller
{
    public function showProductDetail($slug)
    {
        $product = Product::with('images', 'category', 'productReviews.user')
            ->where('slug', $slug)
            ->where('is_active', 1)
            ->firstOrFail();

        $reviews       = $product->productReviews()->with('user')->where('is_visible', true)->latest()->get();
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

    public function updateReview(Request $request, Product $product, ProductReview $review)
    {
        abort_unless(Auth::check() && $review->user_id === Auth::id(), 403);
        abort_unless($review->product_id === $product->id, 404);

        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|max:1000',
        ]);

        $review->update([
            'rating' => $data['rating'],
            'comment' => $data['comment'] ?? null,
        ]);

        return redirect()
            ->route('product.detail', $product->slug)
            ->withFragment('tabs-2')
            ->with('reviewSuccess', 'Đã cập nhật đánh giá.');
    }

    public function destroyReview(Product $product, ProductReview $review)
    {
        abort_unless(Auth::check() && $review->user_id === Auth::id(), 403);
        abort_unless($review->product_id === $product->id, 404);

        $review->delete();

        return redirect()
            ->route('product.detail', $product->slug)
            ->withFragment('tabs-2')
            ->with('reviewSuccess', 'Đã xóa đánh giá.');
    }
}
