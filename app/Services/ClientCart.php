<?php

namespace App\Services;

use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;

/**
 * Giỏ hàng khách hàng (Client)
 *
 * QUY TẮC:
 * - Khách vãng lai (chưa login): lưu giỏ trong SESSION key "cart"
 *   Cấu trúc: [ product_id => số_lượng ]
 * - Khách đã login: lưu giỏ trong bảng "carts"
 * - Chỉ khi vào CHECKOUT mới bắt buộc đăng nhập
 * - Khi login: gộp giỏ session vào DB rồi xóa session
 */
class ClientCart
{
    public const SESSION_KEY = 'cart';

    /**
     * Lấy danh sách item giỏ (dạng object thống nhất để Blade dùng chung).
     * Mỗi item có: product_id, quantity, product
     */
    public static function items(): Collection
    {
        if (Auth::check()) {
            return Auth::user()->carts()->with('product.images')->get();
        }

        $sessionCart = session(self::SESSION_KEY, []);
        if (empty($sessionCart)) {
            return collect();
        }

        $products = Product::with('images')
            ->whereIn('id', array_keys($sessionCart))
            ->get()
            ->keyBy('id');

        return collect($sessionCart)
            ->map(function ($quantity, $productId) use ($products) {
                $product = $products->get((int) $productId);
                if (!$product) {
                    return null;
                }

                // Object giả giống Cart model để view không phải viết 2 kiểu
                return (object) [
                    'id' => null,
                    'product_id' => (int) $productId,
                    'quantity' => (int) $quantity,
                    'product' => $product,
                ];
            })
            ->filter()
            ->values();
    }

    /** Số dòng sản phẩm trong giỏ (để hiện badge header) */
    public static function count(): int
    {
        if (Auth::check()) {
            return Auth::user()->carts()->count();
        }

        return count(session(self::SESSION_KEY, []));
    }

    /** Tính tạm tính */
    public static function subtotal(Collection $items = null): float
    {
        $items = $items ?? self::items();

        return (float) $items->sum(function ($item) {
            if (!$item->product) {
                return 0;
            }
            $price = $item->product->sale_price ?? $item->product->price;

            return $price * $item->quantity;
        });
    }

    /** Số lượng sản phẩm đang có trong giỏ (guest/user) */
    public static function quantityInCart(int $productId): int
    {
        if (Auth::check()) {
            return (int) (Auth::user()->carts()->where('product_id', $productId)->value('quantity') ?? 0);
        }

        return (int) (session(self::SESSION_KEY, [])[$productId] ?? 0);
    }

    /**
     * Thêm / cộng dồn số lượng vào giỏ.
     * Ràng buộc: tổng trong giỏ không được vượt tồn kho (products.quantity).
     *
     * @return array{success:bool,message:string,cart_count:int,stock?:int,in_cart?:int}
     */
    public static function add(int $productId, int $quantity = 1): array
    {
        $product = Product::findOrFail($productId);
        $quantity = max(1, $quantity);
        $stock = (int) $product->quantity;
        $inCart = self::quantityInCart($productId);

        // Hết hàng
        if ($stock < 1) {
            return [
                'success' => false,
                'message' => 'Sản phẩm đã hết hàng.',
                'cart_count' => self::count(),
                'stock' => 0,
                'in_cart' => $inCart,
            ];
        }

        // Đã đủ số lượng tối đa trong giỏ
        if ($inCart >= $stock) {
            return [
                'success' => false,
                'message' => "Bạn đã thêm tối đa {$stock} sản phẩm (số lượng hiện có trong kho).",
                'cart_count' => self::count(),
                'stock' => $stock,
                'in_cart' => $inCart,
            ];
        }

        // Cộng thêm sẽ vượt tồn kho
        $requestedTotal = $inCart + $quantity;
        if ($requestedTotal > $stock) {
            $canAdd = $stock - $inCart;

            return [
                'success' => false,
                'message' => "Chỉ còn {$stock} sản phẩm trong kho. Bạn đang có {$inCart} trong giỏ, chỉ có thể thêm tối đa {$canAdd} nữa.",
                'cart_count' => self::count(),
                'stock' => $stock,
                'in_cart' => $inCart,
            ];
        }

        $newQty = $requestedTotal;

        if (Auth::check()) {
            $cart = Auth::user()->carts()->where('product_id', $productId)->first();

            if ($cart) {
                $cart->update(['quantity' => $newQty]);
            } else {
                Auth::user()->carts()->create([
                    'product_id' => $productId,
                    'quantity' => $newQty,
                ]);
            }
        } else {
            $cart = session(self::SESSION_KEY, []);
            $cart[$productId] = $newQty;
            session([self::SESSION_KEY => $cart]);
        }

        return [
            'success' => true,
            'message' => 'Đã thêm vào giỏ hàng',
            'cart_count' => self::count(),
            'stock' => $stock,
            'in_cart' => $newQty,
        ];
    }

    /**
     * Cập nhật số lượng theo product_id (dùng chung guest + user).
     * Ràng buộc: 1 ≤ số lượng ≤ tồn kho.
     */
    public static function update(int $productId, int $quantity): array
    {
        $product = Product::findOrFail($productId);
        $stock = (int) $product->quantity;

        if ($stock < 1) {
            return [
                'success' => false,
                'message' => 'Sản phẩm đã hết hàng. Vui lòng xóa khỏi giỏ.',
                'cart_count' => self::count(),
                'stock' => 0,
            ];
        }

        if ($quantity < 1) {
            return [
                'success' => false,
                'message' => 'Số lượng tối thiểu là 1.',
                'cart_count' => self::count(),
                'stock' => $stock,
            ];
        }

        // Nhập quá số lượng đang có → từ chối, không tự cắt im lặng
        if ($quantity > $stock) {
            return [
                'success' => false,
                'message' => "Số lượng vượt quá tồn kho. Hiện chỉ còn {$stock} sản phẩm.",
                'cart_count' => self::count(),
                'stock' => $stock,
            ];
        }

        if (Auth::check()) {
            $cart = Auth::user()->carts()->where('product_id', $productId)->firstOrFail();
            $cart->update(['quantity' => $quantity]);
        } else {
            $cart = session(self::SESSION_KEY, []);
            if (!isset($cart[$productId])) {
                abort(404);
            }
            $cart[$productId] = $quantity;
            session([self::SESSION_KEY => $cart]);
        }

        return [
            'success' => true,
            'message' => 'Đã cập nhật giỏ hàng',
            'cart_count' => self::count(),
            'stock' => $stock,
        ];
    }

    /** Xóa 1 sản phẩm khỏi giỏ theo product_id */
    public static function remove(int $productId): array
    {
        if (Auth::check()) {
            Auth::user()->carts()->where('product_id', $productId)->delete();
        } else {
            $cart = session(self::SESSION_KEY, []);
            unset($cart[$productId]);
            session([self::SESSION_KEY => $cart]);
        }

        return [
            'success' => true,
            'message' => 'Đã xóa sản phẩm khỏi giỏ',
            'cart_count' => self::count(),
        ];
    }

    /**
     * Khi khách login: gộp giỏ session vào DB.
     * Nếu SP đã có trong DB → cộng dồn số lượng (không vượt tồn kho).
     */
    public static function mergeSessionToUser(User $user): void
    {
        $sessionCart = session(self::SESSION_KEY, []);
        if (empty($sessionCart)) {
            return;
        }

        foreach ($sessionCart as $productId => $quantity) {
            $product = Product::find($productId);
            if (!$product || $product->quantity < 1) {
                continue;
            }

            $qty = max(1, (int) $quantity);
            $existing = $user->carts()->where('product_id', $productId)->first();

            if ($existing) {
                $existing->update([
                    'quantity' => min($existing->quantity + $qty, $product->quantity),
                ]);
            } else {
                $user->carts()->create([
                    'product_id' => $productId,
                    'quantity' => min($qty, $product->quantity),
                ]);
            }
        }

        session()->forget(self::SESSION_KEY);
    }

    /** Xóa hết giỏ của user (sau khi đặt hàng thành công) */
    public static function clearUserCart(User $user): void
    {
        $user->carts()->delete();
        session()->forget(self::SESSION_KEY);
    }

    /**
     * Đưa lại sản phẩm từ đơn đã hủy (VD: hủy VNPay) về giỏ user.
     * Gọi SAU khi đã hoàn tồn kho.
     */
    public static function restoreFromOrder(\App\Models\Order $order): void
    {
        $user = $order->user;
        if (!$user) {
            return;
        }

        $order->loadMissing('orderItems');

        foreach ($order->orderItems as $item) {
            $product = Product::find($item->product_id);
            if (!$product || (int) $product->quantity < 1) {
                continue;
            }

            $qty = min((int) $item->quantity, (int) $product->quantity);
            if ($qty < 1) {
                continue;
            }

            $existing = $user->carts()->where('product_id', $item->product_id)->first();
            if ($existing) {
                $existing->update([
                    'quantity' => min($existing->quantity + $qty, (int) $product->quantity),
                ]);
            } else {
                $user->carts()->create([
                    'product_id' => $item->product_id,
                    'quantity' => $qty,
                ]);
            }
        }
    }
}
