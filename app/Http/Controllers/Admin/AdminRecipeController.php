<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminRecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //Hiển thị danh sách món ăn
        $recipes = Recipe::latest()->paginate(10);
        return view('admin.recipes.index', compact('recipes'));
        
    }

    /**
     * Show the form for creating a new resource.
     */
    //Form thêm mới
    public function create()
    {
        $products = Product::where('is_active', 1)->get();
        return view('admin.recipes.create',compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    //xử lý luuw trên data
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'product_ids' => 'array',
            'image'=>'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ],[
            'title.required'=>'Bạn phải nhật tên món ăn',
        ]);
        //Tạo món 
        $recipes = new Recipe();
        $recipes->title = $request-> title;
        $recipes->slug = Str::slug($request->title);
        $recipes->is_active = $request->has('is_active') ? 1:0;
        if($request->hasFile('image')){
            $imagePath = $request->file('image')->store('recipes','public');
            $recipes->image=$imagePath;
        }
        $recipes->save();

        //Lưu nguyên liệu vào bảng tring gian
        if($request->has('product_ids')){
            $recipes->products()->attach($request->product_ids);
        }
    
        return redirect()->route('admin.recipes.index')->with('success','Thêm món ăn thành công');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $recipe = Recipe::findOrFail($id);
        $products = Product::where('is_active',1)->get();
        return view('admin.recipes.edit',compact('recipe','products'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'title'=>'required|string|max:255',
            'product_ids'=>'array',
            'image'=>'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ],[
            'title.required'=>'Bạn phải nhập tên món ăn',
        ]);
        //Tìm món ăn cần cập nhật
        $recipe = Recipe::findOrFail($id);
        //Cập nhật thông tin
        $recipe->title = $request->title;
        $recipe->slug = Str::slug($request->title);
        $recipe->is_active = $request->has('is_active') ? 1:0;

        //Cập nhật ảnh
        if($request->hasFile('image')){
            $imagePath = $request->file('image')->store('recipes','public');
            $recipe->image=$imagePath;
        }
        $recipe->save();

        //Cập nhật nguyên liệu
        if($request->has('product_ids')){
            $recipe->products()->sync($request->product_ids);
        }else{
            $recipe->products()->sync([]);
        }

        return redirect()->route('admin.recipes.index')->with('success','Cập nhật món ăn thành công');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $recipe=Recipe::findOrFail($id);
        $recipe->delete();
        return redirect()->route('admin.recipes.index')->with('success','Xóa món ăn thành công');
    }
}
