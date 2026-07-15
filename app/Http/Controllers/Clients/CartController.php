<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Services\ClientCart;
use Illuminate\Http\Request;

/**
 * Giỏ hàng
 *
 * FLOW:
 * 1. Guest / User đều xem giỏ, thêm, sửa, xóa được (không cần login)
 * 2. Guest lưu SESSION, User lưu DB — xem App\Services\ClientCart
 * 3. Bấm "Thanh toán" → route checkout có middleware auth → mới bắt login
 * 4. Thêm/sửa số lượng: không được vượt products.quantity (tồn kho)
 */
class CartController extends Controller
{
    /** Trang giỏ hàng */
    public function index()
    {
        $cartItems = ClientCart::items();
        $subtotal = ClientCart::subtotal($cartItems);

        return view('clients.pages.cart', compact('cartItems', 'subtotal'));
    }

    /** Thêm vào giỏ (AJAX) — ai cũng dùng được */
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'nullable|integer|min:1',
        ]);

        $result = ClientCart::add(
            (int) $request->product_id,
            (int) ($request->quantity ?? 1)
        );

        return response()->json($result, $result['success'] ? 200 : 422);
    }

    /**
     * Cập nhật số lượng (AJAX trang giỏ).
     * {product} = product_id
     */
    public function update(Request $request, int $product)
    {
        $request->validate(['quantity' => 'required|integer|min:1']);

        $result = ClientCart::update($product, (int) $request->quantity);

        // Vượt tồn kho → trả JSON lỗi để JS hiện toast (không cập nhật HTML)
        if (!$result['success']) {
            return response()->json($result, 422);
        }

        // Thành công → trả lại HTML trang giỏ
        return $this->index();
    }

    /** Xóa sản phẩm khỏi giỏ theo product_id */
    public function destroy(int $product)
    {
        ClientCart::remove($product);

        return $this->index();
    }
}
