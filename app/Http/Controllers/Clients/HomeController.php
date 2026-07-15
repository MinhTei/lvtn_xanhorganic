<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        // Chỉ lấy danh mục gốc (parent_id = null) và kèm theo danh mục con
        $categories = Category::whereNull('parent_id')->with('children')->get();
        
        $products = Product::with('images')->where('is_active', 1)->paginate(12);

        $featuredProducts = Product::with('images')
            ->where('is_active', 1)
            ->where('is_featured', true)
            ->latest()
            ->take(6)
            ->get();

        $latestProducts = Product::with('images')
            ->where('is_active', 1)
            ->latest()
            ->take(6)
            ->get();

        return view('clients.pages.home', compact('categories', 'products', 'featuredProducts', 'latestProducts'));
    }
    
}
