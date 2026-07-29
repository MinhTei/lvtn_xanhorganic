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
            $q = trim($request->q);
            $query->where(function ($builder) use ($q) {
                $builder->where('name', 'like', "%{$q}%")
                    ->orWhere('description', 'like', "%{$q}%")
                    ->orWhere('slug', 'like', "%{$q}%");
            });
        }


        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        



        $min = $request->input('minPrice', $request->input('min_price'));
        $max = $request->input('maxPrice', $request->input('max_price'));
        if ($min !== null && $min !== '') {
            $query->where('price', '>=', $min);
        }
        if ($max !== null && $max !== '') {
            $query->where('price', '<=', $max);
        }

        match ($request->sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            default      => $query->latest('id'), 
        };

        $products = $query->paginate(6)->withQueryString();

        return view('clients.pages.products', compact('categories', 'products'));
    }
}
