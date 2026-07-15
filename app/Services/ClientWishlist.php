<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * Danh sách yêu thích
 *
 * QUY TẮC:
 * - Guest: SESSION key "wishlist" = [product_id, product_id, ...]
 * - User đã login: bảng "wishlists"
 * - Guest/User đều thêm/xem/xóa được — KHÔNG bắt login
 * - Khi login: gộp wishlist session vào DB
 */
class ClientWishlist
{
    public const SESSION_KEY = 'wishlist';

    /** Lấy list item (object có id + product) để Blade/AJAX dùng chung */
    public static function items(): Collection
    {
        if (Auth::check()) {
            return Auth::user()->wishlists()->with('product.images')->get();
        }

        $ids = array_map('intval', session(self::SESSION_KEY, []));
        if (empty($ids)) {
            return collect();
        }

        return Product::with('images')
            ->whereIn('id', $ids)
            ->get()
            ->map(function ($product) {
                return (object) [
                    'id' => null, // guest không có wishlist row id
                    'product' => $product,
                ];
            });
    }

    public static function count(): int
    {
        if (Auth::check()) {
            return Auth::user()->wishlists()->count();
        }

        return count(session(self::SESSION_KEY, []));
    }

    /** @return array{success:bool,message:string,wishlist_count:int} */
    public static function add(int $productId): array
    {
        Product::findOrFail($productId);

        if (Auth::check()) {
            $user = Auth::user();
            $exists = $user->wishlists()->where('product_id', $productId)->exists();

            if ($exists) {
                return [
                    'success' => true,
                    'message' => 'Sản phẩm đã có trong danh sách yêu thích',
                    'wishlist_count' => $user->wishlists()->count(),
                ];
            }

            $user->wishlists()->create(['product_id' => $productId]);

            return [
                'success' => true,
                'message' => 'Thêm vào danh sách yêu thích thành công',
                'wishlist_count' => $user->wishlists()->count(),
            ];
        }

        $wishlist = array_map('intval', session(self::SESSION_KEY, []));

        if (!in_array($productId, $wishlist, true)) {
            $wishlist[] = $productId;
            session([self::SESSION_KEY => array_values($wishlist)]);

            return [
                'success' => true,
                'message' => 'Đã thêm yêu thích thành công',
                'wishlist_count' => count($wishlist),
            ];
        }

        return [
            'success' => false,
            'message' => 'Sản phẩm đã có trong danh sách yêu thích',
            'wishlist_count' => count($wishlist),
        ];
    }

    /**
     * Xóa yêu thích.
     * - User: truyền wishlist row id
     * - Guest: truyền product_id
     */
    public static function remove(?int $wishlistId = null, ?int $productId = null): array
    {
        if (Auth::check()) {
            Auth::user()->wishlists()->where('id', $wishlistId)->delete();

            return [
                'success' => true,
                'message' => 'Đã xóa khỏi danh sách yêu thích',
                'wishlist_count' => Auth::user()->wishlists()->count(),
            ];
        }

        $productId = (int) $productId;
        $wishlist = collect(session(self::SESSION_KEY, []))
            ->map(fn ($id) => (int) $id)
            ->reject(fn ($id) => $id === $productId)
            ->values()
            ->all();

        session([self::SESSION_KEY => $wishlist]);

        return [
            'success' => true,
            'message' => 'Đã xóa khỏi danh sách yêu thích',
            'wishlist_count' => count($wishlist),
        ];
    }

    /** Gộp wishlist session → DB khi login */
    public static function mergeSessionToUser(User $user): void
    {
        $ids = array_map('intval', session(self::SESSION_KEY, []));
        if (empty($ids)) {
            return;
        }

        foreach ($ids as $productId) {
            if (!Product::where('id', $productId)->exists()) {
                continue;
            }
            $user->wishlists()->firstOrCreate(['product_id' => $productId]);
        }

        session()->forget(self::SESSION_KEY);
    }
}
