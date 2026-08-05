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

        $categories = Category::where('parent_id', null)->with('children')->get();

        $query = Product::with('images')->where('is_active', 1);

        if ($request->filled('q')) {
            $q = mb_strtolower(trim($request->q), 'UTF-8');
            $query->where(function ($builder) use ($q) {
                $builder->whereRaw('LOWER(name) LIKE ?', ["%{$q}%"])
                    ->orWhereRaw('LOWER(slug) COLLATE utf8mb4_vi_0900_as_cs LIKE ?', ["%{$q}%"]);
            });
        }


        if ($request->filled('category_id')) {
            $categoryId = $request->category_id;
            $childIds = Category::where('parent_id', $categoryId)->pluck('id')->toArray();
            $allCategoryIds = array_merge([$categoryId], $childIds);

            $query->whereIn('category_id', $allCategoryIds);
        }




        $min = $request->input('minPrice', $request->input('min_price'));
        $max = $request->input('maxPrice', $request->input('max_price'));

        if ($min !== null && $min !== '') {
            $query->whereRaw('COALESCE(sale_price, price) >= ?', [$min]);
        }
        if ($max !== null && $max !== '') {
            $query->whereRaw('COALESCE(sale_price, price) <= ?', [$max]);
        }

        match ($request->sort) {
            'price_asc' => $query->orderByRaw('COALESCE(sale_price, price) asc'),
            'price_desc' => $query->orderByRaw('COALESCE(sale_price, price) desc'),
            default => $query->latest('id'),
        };

        $products = $query->paginate(8)->withQueryString();

        return view('clients.pages.products', compact('categories', 'products'));
    }
}
