<?php

namespace App\Services;

use App\Models\Order;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

class ClientCart
{
    public const SESSION_KEY = 'cart';

    public static function items(): Collection
    {
        if (Auth::check()) {
            return Auth::user()->carts()->with('product.images')->get();
        }

        $cartSession = session('cart', []);
        //Nếu trống thì return về 1 mảng rỗng
        if (empty($cartSession)) {
            return collect();
        }

        //id trong session
        $listProductId = array_keys($cartSession);
        $products = Product::with('images')->whereIn('id', $listProductId)->get()->keyBy('id');
        $resultCart = collect();

        // session 
        foreach ($cartSession as $productId => $quantity) {
            $product = $products->get($productId);
            if ($product) {
                //đóng gói giống dữ liệu lấy từ data
                $fakeProduct = (object) [
                    'product_id' => $productId,
                    'quantity' => $quantity,
                    'product' => $product,
                ];
                $resultCart->push($fakeProduct);
            }
        }
        return $resultCart;
    }

    public static function count(): int
    {
        if (Auth::check()) {
            return Auth::user()->carts()->count();
        }
        $cartSession = session('cart', []);
        return count($cartSession);
    }

    public static function subtotal(Collection $items = null): float
    {
        if ($items == null) {
            $items = self::items();
        }
        $totalMoney = 0;
        foreach ($items as $item) {
            if (!$item->product) {
                continue;
            }
            $price = $item->product->sale_price ?? $item->product->price;
            $totalTemp = $price * $item->quantity;
            $totalMoney += $totalTemp;
        }
        return (float) $totalMoney;
    }

    public static function quantityInCart(int $productId): int
    {
        //Kịch 1
        if (Auth::check()) {
            $cartItem = (Auth::user()->carts()
                ->where('product_id', $productId)->first());
            if ($cartItem) {
                return (int) $cartItem->quantity;
            } else {
                return 0;
            }

        }
        //Kich 2
        $cartSession = session('cart', []);
        if (isset($cartSession[$productId])) {
            return (int) $cartSession[$productId];
        } else {
            return 0;
        }

    }

    public static function update(int $productId, int $quantity): array
    {
        //Kiểm kho
        $product = Product::findOrFail(($productId));
        if ($product->is_active == false) {
            return [
                'success' => false,
                'message' => 'Sản phẩm đã ngừng bán',
                'cart_count' => self::count(),
            ];
        }
        $stock = $product->quantity;
        if ($stock < 1) {
            return [
                'success' => false,
                'message' => 'Sản phẩm đã hết hàng',
                'cart_count' => self::count(),
            ];
        }
        if ($quantity < 1) {
            return [
                'success' => false,
                'message' => 'Số lượng tối thiểu là 1',
                'cart_count' => self::count(),
            ];
        }
        //Chặn quá số lượng
        $clamped = false;
        if ($quantity > $stock) {
            $quantity = $stock;
            $clamped = true;
        }

        //Lưu trữ
        if (Auth::check()) {
            $cartItem = Auth::user()->carts->where('product_id', $productId)->first();
            if ($cartItem) {
                $cartItem->update(['quantity' => $quantity]);
            }
        } else {
            $cartSession = session('cart', []);
            if (isset($cartSession[$productId])) {
                $cartSession[$productId] = $quantity;
                session(['cart' => $cartSession]);
            }
        }

        // trả về kết quả
        return [
            'success' => true,
            'message' => $clamped ? 'Số lượng vượt kho, Tự động giảm về tối đa' : "Đã cập nhật giỏ hàng",
            'cart_count' => self::count(),
        ];

    }


    public static function add(int $productId, int $quantity = 1): array
    {
        $product = Product::findOrFail($productId);

        if ($product->is_active == false) {
            return [
                'success' => false,
                'message' => "Sản phẩm đã ngừng bán.",
                'cart_count' => self::count()
            ];
        }

        $stock = $product->quantity;

        //số lượng đang có trong giỏ
        $inCart = self::quantityInCart($productId);
        if ($stock < 1) {
            return [
                'success' => false,
                'message' => "Sản phẩm đã hết hàng.",
            ];
        }
        $requestedTotal = $quantity + $inCart;
        //Kiểm tra nếu vượt
        if ($requestedTotal > $stock) {
            return [
                'success' => false,
                'message' => "chỉ còn {$stock} trong kho hiện tại bạn đang có {$inCart} trong giỏ. ",
            ];
        }

        //Chốt số lượng
        $newQty = $requestedTotal;
        if (Auth::check()) {
            $cartItem = Auth::user()->carts()->where('product_id', $productId)->first();
            if ($cartItem) {
                $cartItem->update(['quantity' => $newQty]);
            } else {
                Auth::user()->carts()->create([
                    'product_id' => $productId,
                    'quantity' => $newQty,
                ]);
            }
        } else {
            $cartSession = session('cart', []);
            $cartSession[$productId] = $newQty;
            session(['cart' => $cartSession]);
        }
        return [
            'success' => true,
            'message' => 'Đã thêm giỏ hàng thành công!',
            'cart_count' => self::count()
        ];
    }

    public static function remove(int $productId): array
    {
        if (Auth::check()) {
            Auth::user()->carts()->where('product_id', $productId)->delete();
        } else {
            //Láy seeion ảo nếu khách chưa đn
            $cartSession = session('cart', []);
            if (isset($cartSession[$productId])) {
                unset($cartSession[$productId]);
                session(['cart' => $cartSession]);
            }
        }
        //Báo ra ngoài
        return [
            'success' => true,
            'message' => 'Xóa khỏi giỏ thành công',
            'cart_count' => self::count()
        ];
    }


    public static function mergeSessionToUser(User $user): void
    {

        $cartSession = session('cart', []);
        if (empty($cartSession)) {
            return;
        }

        foreach ($cartSession as $productId => $quantity) {
            $product = Product::find($productId);
            if (!$product || $product->quantity < 1) {
                continue;
            }
            $productExistDB = $user->carts()->where('product_id', $productId)->first();

            if ($productExistDB) {
                $totalInCart = $productExistDB->quantity + $quantity;
                //ép ko vượt kho
                $finalQty = min($totalInCart, $product->quantity);
                //update DB
                $productExistDB->update([
                    'quantity' => $finalQty,
                ]);

            } else {
                $user->carts()->create([
                    'product_id' => $productId,
                    'quantity' => min($quantity, $product->quantity),
                ]);
            }

        }
        session()->forget('cart');
    }

    public static function clearUserCart(User $user): void
    {
        $user->carts()->delete();
        session()->forget('cart');
    }

    
    public static function restoreFromOrder(Order $order): void
    {
        $user = $order->user;
        $order->loadMissing('orderItems');
        foreach ($order->orderItems as $item) {
            //Vào kho xem có ko
            $product = Product::find($item->product_id);

            //Nếu lỡ hủy mà vừa lúc mất hàng
            if (!$product || $product->quantity < 1) {
                continue;
            }
            //Lấy số lượng cũ
            $qtyCancelBack = min($item->quantity, $product->quantity);
            //Nhét lại vào giỏ DB
            $productExistDB = $user->carts()->where('product_id', $item->product_id)->first();
            if ($productExistDB) {
                $totalInCart = $productExistDB->quantity + $qtyCancelBack;

                $productExistDB->update([
                    'quantity' => min($totalInCart, $product->quantity),
                ]);
            } else {
                $user->carts()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $qtyCancelBack,
                ]);
            }

        }

    }
}
