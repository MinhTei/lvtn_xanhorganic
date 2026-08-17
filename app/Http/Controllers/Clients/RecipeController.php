<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Models\Recipe;
use App\Services\ClientCart;
use Illuminate\Http\Request;

class RecipeController extends Controller
{

    public function index()
    {
        $recipes = Recipe::where('is_active', 1)->paginate(12);
        return view('clients.pages.recipes', compact('recipes'));
    }



    public function show(Request $request, $id)
    {
        $recipe = Recipe::with('products')->findOrFail($id);

        if ($request->ajax() || $request->wantsJson()) {
            return view('clients.partials.recipe_items', compact('recipe'))->render();
        }

        return redirect()->route('recipes');
    }

    public function addAllToCart(Request $request, $id)
    {
        $recipe = Recipe::with('products')->findOrFail($id);
        
        if ($recipe->products->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'Món ăn này chưa có nguyên liệu nào.',
            ], 422);
        }

        $added = 0;
        $skipped = 0;
        $messages = [];

        foreach ($recipe->products as $product) {
            $result = ClientCart::add((int) $product->id, 1);
            if ($result['success']) {
                $added++;
            } else {
                $skipped++;
                $messages[] = $product->name . ': ' . $result['message'];
            }
        }

        $msg = "Đã thêm {$added} sản phẩm vào giỏ hàng.";
        if ($skipped > 0) {
            $msg .= " Bỏ qua {$skipped} sản phẩm.";
        }

        return response()->json([
            'success' => $added > 0,
            'message' => $msg,
            'details' => $messages,
            'cart_count' => ClientCart::count(),
        ], $added > 0 ? 200 : 422);
    }
}
