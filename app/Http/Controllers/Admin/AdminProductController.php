<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminProductController extends Controller implements HasMiddleware
{

    public static function middleware(): array
    {
        return [
            new Middleware('permission:add_products', only: ['create', 'store', 'importForm', 'importTemplate', 'importStore']),
            new Middleware('permission:edit_products', only: ['edit', 'update']),
            new Middleware('permission:delete_products', only: ['destroy']),
        ];
    }    public function index(Request $request)
    {
        $query = Product::with('category')->latest();
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
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === '1');
        }

        if ($request->filled('alert')) {
            if ($request->alert === 'low_stock') {
                $query->where('quantity', '<=', 10);
            } elseif ($request->alert === 'near_expiry') {
                $query->whereBetween('expiry_date', [now(), now()->addDays(5)]);
            }
        }

        $products = $query->paginate(12)->withQueryString();

        $categories = Category::where('is_active',1)->whereNull('parent_id')->get();

        $lowStock = Product ::where('quantity','<=',10)->count();
        $lowDate = Product::whereBetween('expiry_date',[now(), now()->addDays(5)])->count();
        return view('admin.products.index', compact('products', 'categories', 'lowStock', 'lowDate'));
    }


    public function create()
    {
        $categories = Category::where('is_active', 1)
            ->whereNotNull('parent_id')
            ->orderBy('name')->get();
        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
      $data = $this -> validated($request);
      
      $data['is_active'] = $request->boolean('is_active');
      $data['is_featured'] = $request->boolean('is_featured');
      $data['unit'] = $data['unit'] ?: 'sản phẩm';
      $data['delivery_mode'] = $data['delivery_mode'] ?: 'both';
      $data['slug'] = $this->uniqueSlug($data['name']);
      $product = Product::create($data);
      if($request->filled('manufacture_date') && $product->category->shelf_days !== null){
        $shelfDays = $product->category->shelf_days;
        $product->manufacture_date = $request->manufacture_date;
        $product->expiry_date = \Carbon\Carbon::parse($request->manufacture_date)->addDays($shelfDays);
        $product->save();
      }else{
        $product->manufacture_date = $request->filled('manufacture_date') ? $request->manufacture_date : null;
        $product->expiry_date = null; // Không có shelf_days → Không tính HSD
        $product->save();
      }
      $this->storeUploadedImages($request,$product);
      return redirect()->route('admin.products.index')->with('success','Thêm mới thành công');
    }

    public function edit(Product $product)
    {
        $product->load('images');
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validated($request, $product->id);

        if ($product->name !== $data['name']) {
            $data['slug'] = $this->uniqueSlug($data['name'], $product->id);
        }

        $product->update($data);
        if($request->filled('manufacture_date') && $product->category->shelf_days !== null){
        $shelfDays = $product->category->shelf_days;
        $product->manufacture_date = $request->manufacture_date;
        $product->expiry_date = \Carbon\Carbon::parse($request->manufacture_date)->addDays($shelfDays);
        $product->save();
      }else{
        $product->manufacture_date = $request->filled('manufacture_date') ? $request->manufacture_date : null;
        $product->expiry_date = null; // Không có shelf_days → Không tính HSD
        $product->save();
      }
        
        $this->deleteSelectedImages($request, $product);
        $this->storeUploadedImages($request, $product);
        $this->ensurePrimaryImage($product);

        return redirect()->route('admin.products.index')
            ->with('success', 'Cập nhật sản phẩm thành công.');
    }

    public function destroy(Product $product)
    {
        if ($product->orderItems()->exists()) {
            $product->update(['is_active' => false]);

            return redirect()->back()
                ->with('success', 'Sản phẩm đã có trong đơn hàng → đã ẩn bán (không xóa để giữ lịch sử).');
        }

        $product->load('images');
        foreach ($product->images as $image) {
            $image->deleteFileIfStored();
            $image->delete();
        }
        $product->delete();

        return redirect()->back()
            ->with('success', 'Đã xóa sản phẩm.');
    }

    public function importForm()
    {
        return view('admin.products.import');
    }

    public function importTemplate()
    {
        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => 'attachment; filename="products_import_template.csv"',
        ];

        $callback = function () {
            $out = fopen('php://output', 'w');
            // BOM UTF-8 cho Excel
            fwrite($out, "\xEF\xBB\xBF");
            fputcsv($out, [
                'name',
                'category_slug',
                'price',
                'sale_price',
                'quantity',
                'unit',
                'manufacture_date',
                'description',
                'is_featured',
                'is_active',
                'delivery_mode',
                'pricing_mode',
                'image_url',
            ]);
            fputcsv($out, [
                'Rau cải hữu cơ',
                'rau-cu',
                '35000',
                '30000',
                '100',
                'kg',
                '2026-08-01',
                'Rau sạch',
                '0',
                '1',
                'both',
                'standard',
                '',
            ]);
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    public function importStore(Request $request)
{
    $request->validate(['file' => 'required|file|mimes:csv,txt|max:2048']);

    $file = $request->file('file');
    $handle = fopen($file->getRealPath(), 'r'); 

    $firstLine = fgets($handle);
    $firstLine = preg_replace('/^\xEF\xBB\xBF/', '', $firstLine);
    $header = str_getcsv($firstLine); 
    
    $created = 0; 
    
    while (($row = fgetcsv($handle)) !== false) {
        

        if (count($header) !== count($row)) continue; 
        $data = array_combine($header, $row);
        
        if (empty($data['name'])) continue;

        $category = Category::where('slug', trim($data['category_slug']))->first();
        if (!$category) continue; 

        $mfgDate = !empty($data['manufacture_date']) ? Carbon::parse(trim($data['manufacture_date'])) : null;

        $product = Product::create([
            'category_id'      => $category->id,
            'name'             => trim($data['name']),
            'slug'             => $this->uniqueSlug(trim($data['name'])),
            'price'            => (float) $data['price'],
            'sale_price'       => !empty($data['sale_price']) ? (float) $data['sale_price'] : null,
            'quantity'         => (int) $data['quantity'],
            'unit'             => !empty($data['unit']) ? trim($data['unit']) : 'sp',
            'manufacture_date' => $mfgDate?->toDateString(),
            'expiry_date'      => ($mfgDate && $category->shelf_days !== null)
                                    ? $mfgDate->copy()->addDays($category->shelf_days)->toDateString()
                                    : null,
            'description'      => $data['description'] ?? null,
            'is_featured'      => $data['is_featured'] == '1',
            'is_active'        => $data['is_active'] != '0',
            'delivery_mode'    => !empty($data['delivery_mode']) ? trim($data['delivery_mode']) : 'both',
            'pricing_mode'     => in_array(trim($data['pricing_mode'] ?? ''), ['standard', 'daily_cycle'])
                                    ? trim($data['pricing_mode'])
                                    : 'standard',
        ]);

        if (!empty($data['image_url'])) {
            ProductImage::create([
                'product_id' => $product->id,
                'image_url'  => trim($data['image_url']),
                'is_primary' => true,
            ]);
        }

        $created++; 
    }

    fclose($handle); 

    return redirect()->route('admin.products.index')->with('success', "Import thành công {$created} sản phẩm.");
}

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'sale_price' => 'nullable|numeric|min:0|lt:price',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required|string|max:50',
            'delivery_mode' => 'required|in:standard,express,both',
            'pricing_mode' => 'nullable|in:standard,daily_cycle',
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'manufacture_date'=>'required|date',
            'images' => 'nullable|array|max:8',
            'images.*' => 'image|mimes:jpeg,jpg,png,webp,gif|max:2048',
            'delete_images' => 'nullable|array',
            'delete_images.*' => 'integer|exists:product_images,id',
        ], [
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'price.required' => 'Vui lòng nhập giá.',
            'sale_price.lt' => 'Giá khuyến mãi phải nhỏ hơn giá gốc.',
            'quantity.required' => 'Vui lòng nhập tồn kho.',
            'unit.required' => 'Vui lòng nhập đơn vị.',
            'delivery_mode.required' => 'Vui lòng chọn loại giao hàng.',
            'images.*.image' => 'File phải là hình ảnh.',
            'images.*.max' => 'Mỗi ảnh tối đa 2MB.',
            'manufacture_date.required'=>'Vui lòng chọn ngày sản xuất.',
        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');
        $data['sale_price'] = $data['sale_price'] ?? null;

        unset($data['images'], $data['delete_images']);

        return $data;
    }

    private function storeUploadedImages(Request $request, Product $product): void
    {
        if(!$request->hasFile('images')){
            return;
        }
        $hasPrimary = $product->images()->where('is_primary',true)->exists();

        foreach($request->file('images') as $file){
            if(!$file->isValid()){
                continue;
            }
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeName = \Illuminate\Support\Str::slug($originalName);
            // Ghép thêm mã thời gian để chống bị trùng tên file
            $filename = $safeName . '-' . time() . '.' . $file->getClientOriginalExtension();

            // Lưu file với tên an toàn
            $path = $file->storeAs('products/' . $product->slug, $filename, 'public');
            $isPrimary = false;
            if($hasPrimary == false){
                $isPrimary = true;
                $hasPrimary = true;
            }
            ProductImage::create([
                'product_id'=>$product->id,
                'image_url'=>Storage::url($path),
                'is_primary'=>$isPrimary,
            ]);
        }
    }

    private function deleteSelectedImages(Request $request, Product $product): void
    {
        $ids = $request->input('delete_images', []);
        if ( empty($ids)) {
            return;
        }

        $images = $product->images()->whereIn('id', $ids)->get();
        foreach ($images as $image) {
            $image->deleteFileIfStored();
            $image->delete();
        }
    }

    private function ensurePrimaryImage(Product $product): void
    {
        $product->refresh()->load('images');
        if ($product->images->isEmpty()) {
            return;
        }

        if ($product->images->where('is_primary', true)->isEmpty()) {
            $product->images->first()->update(['is_primary' => true]);
        }
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'san-pham';
        $slug = $base;
        $i = 1;

        while (
            Product::where('slug', $slug)
                ->when($ignoreId, fn($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
