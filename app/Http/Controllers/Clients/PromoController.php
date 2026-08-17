<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;

class PromoController extends Controller
{
    public function index()
    {
        // Lấy các sản phẩm có sale_price 
        $products = Product::with('images')
            ->where('is_active', 1)
            ->whereNotNull('sale_price') // Điều kiện khuyến mãi
            ->paginate(12);

        return view('clients.pages.promos', compact('products'));
    }
}
