<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Services\ClientCart;
use App\Services\ClientWishlist;
use Illuminate\Http\Request;


class WishlistController extends Controller
{
    public function index(Request $request)
    {
        $wishlistItems = ClientWishlist::items();

        if ($request->ajax() || $request->wantsJson()) {
            return view('clients.partials.wishlist_items', compact('wishlistItems'))->render();
        }

        return redirect()->route('home');
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $result = ClientWishlist::add((int) $request->product_id);

        return response()->json($result);
    }


    public function destroy(Request $request, $wishlist = null)
    {
        $result = ClientWishlist::remove(
            $wishlist !== null ? (int) $wishlist : null,
            $request->product_id !== null ? (int) $request->product_id : null
        );

        return response()->json($result);
    }


    public function addAllToCart()
    {
        $items = ClientWishlist::items();
        if ($items->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Danh sách yêu thích trống.',
            ], 422);
        }

        $added = 0;
        $skipped = 0;
        $messages = [];

        foreach ($items as $item) {
            $product = $item->product;
            if (!$product) {
                $skipped++;
                continue;
            }

            $result = ClientCart::add((int) $product->id, 1);
            if ($result['success']) {
                $added++;
            } else {
                $skipped++;
                $messages[] = $product->name . ': ' . $result['message'];
            }
        }

        $msg = "Đã thêm {$added} sản phẩm vào giỏ hàng.";
        if ($skipped > 0) {
            $msg .= " Bỏ qua {$skipped} sản phẩm.";
        }

        ClientWishlist::clearWishlistAll();

        return response()->json([
            'success' => $added > 0,
            'message' => $msg,
            'details' => $messages,
            'cart_count' => ClientCart::count(),
            'wishlist_count' => ClientWishlist::count(),
        ], $added > 0 ? 200 : 422);
    }
}
