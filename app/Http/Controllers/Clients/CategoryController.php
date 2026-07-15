<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;

class CategoryController extends Controller
{
    public function showCategories()
    {
        return redirect()->route('products');
    }

    public function showCategoriesBySlug($slug)
    {
        $categories = Category::whereNull('parent_id')->with('children')->get();
        $category = Category::where('slug', $slug)->firstOrFail();

        if ($category->parent_id == null) {
            $categoriesID = Category::where('parent_id', $category->id)->pluck('id');
            $products = Product::with('images')
                ->whereIn('category_id', $categoriesID)
                ->where('is_active', 1)
                ->paginate(12)
                ->withQueryString();
        } else {
            $products = Product::with('images')
                ->where('category_id', $category->id)
                ->where('is_active', 1)
                ->paginate(12)
                ->withQueryString();
        }

        return view('clients.pages.products', compact('categories', 'category', 'products'));
    }
}
