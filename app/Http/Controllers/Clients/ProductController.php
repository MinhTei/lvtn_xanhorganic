<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        // 1. Lấy danh mục chung cho giao diện
        $categories = Category::where('parent_id', null)->with('children')->get();
        
        // 2. Bắt đầu câu query lấy sản phẩm (luôn lấy những cái đang active)
        $query = Product::with('images')->where('is_active', 1);

        // Tìm kiếm theo tên / mô tả
        if ($request->filled('q')) {
            $q = trim($request->q);
            $query->where(function ($builder) use ($q) {
                $builder->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhere('slug', 'like', "%{$q}%");
            });
        }

        // --- BỘ LỌC TÍCH HỢP CHUNG VÀO HÀM INDEX ---

        // Lọc theo danh mục
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Lọc theo khoảng giá (chấp nhận cả tên cũ minPrice và form min_price)
        $min = $request->input('minPrice', $request->input('min_price'));
        $max = $request->input('maxPrice', $request->input('max_price'));
        if ($min !== null && $min !== '') {
            $query->where('price', '>=', $min);
        }
        if ($max !== null && $max !== '') {
            $query->where('price', '<=', $max);
        }

        // Sắp xếp
        match ($request->sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            default      => $query->latest('id'), // Mặc định là sản phẩm mới nhất
        };

        // 3. Thực thi Query & Phân trang (withQueryString để giữ lại tham số filter trên URL)
        $products = $query->paginate(12)->withQueryString();

        return view('clients.pages.products', compact('categories', 'products'));
    }
}
