<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;


class AdminReviewController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('permission:edit_reviews', only: ['toggle']),
            new Middleware('permission:delete_reviews', only: ['destroy']),
        ];
    }    public function index(Request $request)
    {
        $query = ProductReview::with(['user', 'product', 'order'])->latest();

        if ($request->filled('visible')) {
            $query->where('is_visible', $request->visible === '1');
        }

        if ($request->filled('rating')) {
            $query->where('rating', (int) $request->rating);
        }

        $reviews = $query->paginate(15)->withQueryString();

        return view('admin.reviews.index', compact('reviews'));
    }

    public function toggle(ProductReview $review)
    {
        $review->update(['is_visible' => !$review->is_visible]);

        $msg = $review->is_visible ? 'Đã hiện đánh giá.' : 'Đã ẩn đánh giá.';

        return back()->with('success', $msg);
    }

    public function destroy(ProductReview $review)
    {
        $review->delete();

        return back()->with('success', 'Đã xóa đánh giá.');
    }
}
