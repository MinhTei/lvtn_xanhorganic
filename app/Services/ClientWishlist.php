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

    public static function count(): int
    {
        if (Auth::check()) {
            return Auth::user()->wishlists()->count();

        }
        return count(session(self::SESSION_KEY, []));
    }

    public static function items(): Collection
    {
        if (Auth::check()) {
            return Auth::user()->wishlists()->with('product.images')->get();
        }
        //lấy trong giỏ hàng nhma phải chắc là lấy int
        $wishlistIds = array_map('intval', session(self::SESSION_KEY, []));
        if (empty($wishlistIds)) {
            return collect();
        }
        //lấy thông tin mảng
        return Product::with('images')
            ->whereIn('id', $wishlistIds)
            ->get()
            ->map(function ($product) {
                return (object) [
                    'id' => null, //id bản wisshlist
                    'product' => $product,//dữ liệu sản phẩm
                ];
            });


      
    }
    public static function constains(int $productId): bool
    {
        if (Auth::check()) {
            return Auth::user()->wishlists()->where('product_id', $productId)->exists();
        }
        $wishlistIds = array_map('intval', session(self::SESSION_KEY, []));
        return in_array($productId, $wishlistIds, true);


    }

    public static function add(int $productId): array
    {
        Product::findOrFail($productId);


        if (Auth::check()) {
            $user = Auth::user();
            $existing = $user->wishlists()->where('product_id', $productId)->first();
            if ($existing) {
                $existing->delete();
                return [
                    'success' => true,
                    'action' => 'removed',
                    'message' => 'Đã xóa yêu thích',
                    'wishlist_count' => self::count(),
                ];
            }

            $user->wishlists()->create(['product_id' => $productId]);
            return [
                'success' => true,
                'action' => 'added',
                'message' => 'Thêm yêu thích thành công!',
                'wishlist_count' => self::count(),
            ];
        }
        $wishlistIds = array_map('intval', session(self::SESSION_KEY, []));
        if (in_array($productId, $wishlistIds, true)) {
            $wishlistIds = array_values((array_filter($wishlistIds, fn($id) => $id !== $productId)));
            session([self::SESSION_KEY => $wishlistIds]);
            return [
                'success' => true,
                'action' => 'removed',
                'message' => 'Đã xóa yêu thích',
                'wishlist_count' => count($wishlistIds),
            ];
        }
        $wishlistIds[] = $productId;
        session([self::SESSION_KEY => array_values($wishlistIds)]);
        return [
            'success' => true,
            'action' => 'added',
            'message' => ' Thêm yêu thích thành công!',
            'wishlist_count' => count($wishlistIds),
        ];
       

    }

    public static function remove(?int $wishlistId = null, ?int $productId = null): array
    {
        if (Auth::check()) {
            Auth::user()->wishlists()->where('id', $wishlistId)->delete();
        } else {
            //Báo ra ngoài
            $wishlistIds = array_map('intval', session(self::SESSION_KEY, []));
            $wishlistIds = array_values(array_filter($wishlistIds, fn($id) => $id !== $productId));
            session([self::SESSION_KEY => $wishlistIds]);

        }
        return [
            'success' => true,
            'message' => 'Xóa yêu thích thành công',
            'wishlist_count' => self::count()
        ];
    }

    public static function mergeSessionToUser(User $user): void
    {
        $wishlistIds = array_map('intval', session(self::SESSION_KEY, []));
        if (empty($wishlistIds)) {
            return;
        }
        foreach ($wishlistIds as $productId) {
            $product = Product::find($productId);
            if (!$product) {
                continue;
            }
            $wishlistExistDB = $user->wishlists()->where('product_id', $productId)->first();
            if ($wishlistExistDB) {
                continue;
            }else{
                $user->wishlists()->create([
                    'product_id' => $productId
                ]);
            }
        }
        session()->forget(self::SESSION_KEY);
        

    }
    public static function clearWishlistAll(): void{
        if(Auth::check()){
            Auth::user()->wishlists()->delete();
        }else{
            session()->forget(self::SESSION_KEY);
        }
    }
   
}
