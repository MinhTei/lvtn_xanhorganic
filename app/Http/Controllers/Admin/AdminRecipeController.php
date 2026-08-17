<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Recipe;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

use Illuminate\Support\Str;

class AdminRecipeController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('permission:add_recipes', only: ['create', 'store']),
            new Middleware('permission:edit_recipes', only: ['edit', 'update']),
            new Middleware('permission:delete_recipes', only: ['destroy']),
        ];
    }   
    public function index()
    {
        //Hiển thị danh sách món ăn
        $recipes = Recipe::latest()->paginate(10);
        return view('admin.recipes.index', compact('recipes'));
        
    }

    //Form thêm mới
    public function create()
    {
        $products = Product::where('is_active', 1)->get();
        return view('admin.recipes.create',compact('products'));
    }


    //xử lý luuw trên data
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255|unique:recipes,title',
            'product_ids' => 'array',
            'image'=>'nullable|image|mimes:jpeg,png,jpg,gif,webp,avif|max:5120',
        ],[
            'title.required'=>'Bạn phải nhập tên món ăn',
            'title.unique'=>'Tên món ăn này đã tồn tại, vui lòng nhập tên khác.',
            'image.image'=>'File tải lên phải là hình ảnh.',
            'image.mimes'=>'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp, avif.',
            'image.max'=>'Kích thước ảnh tối đa không được vượt quá 5MB.',
        ]);
        

        //Tạo món 
        $recipes = new Recipe();
        $recipes->title = $request-> title;
        $recipes->slug = Str::slug($request->title);
        $recipes->is_active = $request->has('is_active') ? 1:0;
        if($request->hasFile('image')){
            $file = $request->file('image');
            $filename = $recipes->slug . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/clients/img/recipes'), $filename);
            $recipes->image = 'assets/clients/img/recipes/' . $filename;
        }
        $recipes->save();

        //Lưu nguyên liệu vào bảng tring gian
        if($request->has('product_ids')){
            $recipes->products()->attach($request->product_ids);
        }
    
        return redirect()->route('admin.recipes.index')->with('success','Thêm món ăn thành công');
    }

 
    public function edit(string $id)
    {
        $recipe = Recipe::findOrFail($id);
        $products = Product::where('is_active',1)->get();
        return view('admin.recipes.edit',compact('recipe','products'));

    }

    public function update(Request $request, string $id)
    {
        $request->validate([
            'title'=>'required|string|max:255|unique:recipes,title,'.$id,
            'product_ids'=>'array',
            'image'=>'nullable|image|mimes:jpeg,png,jpg,gif,webp,avif|max:5120',
        ],[
            'title.required'=>'Bạn phải nhập tên món ăn',
            'title.unique'=>'Tên món ăn này đã tồn tại, vui lòng nhập tên khác.',
            'image.image'=>'File tải lên phải là hình ảnh.',
            'image.mimes'=>'Hình ảnh phải có định dạng: jpeg, png, jpg, gif, webp, avif.',
            'image.max'=>'Kích thước ảnh tối đa không được vượt quá 5MB.',
        ]);
        $slug = Str::slug($request->title);
        if (Recipe::where('slug', $slug)->where('id', '!=', $id)->exists()) {
            throw \Illuminate\Validation\ValidationException::withMessages([
                'title' => 'Tên món ăn này đã tồn tại, vui lòng nhập tên khác.',
            ]);
        }

        //Tìm món ăn cần cập nhật
        $recipe = Recipe::findOrFail($id);
        //Cập nhật thông tin
        $recipe->title = $request->title;
        $recipe->slug = $slug;

        $recipe->is_active = $request->has('is_active') ? 1:0;

        //Cập nhật ảnh
        if($request->hasFile('image')){
            $file = $request->file('image');
            $filename = $slug . '.' . $file->getClientOriginalExtension();
            $file->move(public_path('assets/clients/img/recipes'), $filename);
            $recipe->image = 'assets/clients/img/recipes/' . $filename;
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


    public function destroy(string $id)
    {
        $recipe=Recipe::findOrFail($id);
        $recipe->delete();
        return redirect()->route('admin.recipes.index')->with('success','Xóa món ăn thành công');
    }
}
