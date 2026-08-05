<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category')->latest();
        if ($search = $request->get('q')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('slug', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->filled('status')) {
            $query->where('is_active', $request->status === '1');
        }
        $products = $query->paginate(12)->withQueryString();

        $categories = Category::whereNotNull('parent_id')->orderBy('name')->get();


        return view('admin.products.index', compact('products', 'categories'));
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
      if($request->filled('manufacture_date')){
        $shelfDays = $product->category->shelf_days ?? 7;
        $product->manufacture_date = $request->manufacture_date;
        $product->expiry_date = \Carbon\Carbon::parse($request->manufacture_date)->addDays($shelfDays);
        $product->save();
      }else{
        $product->manufacture_date = null;
        $product->expiry_date = null;
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
        if($request->filled('manufacture_date')){
        $shelfDays = $product->category->shelf_days ?? 7;
        $product->manufacture_date = $request->manufacture_date;
        $product->expiry_date = \Carbon\Carbon::parse($request->manufacture_date)->addDays($shelfDays);
        $product->save();
      }else{
        $product->manufacture_date = null;
        $product->expiry_date = null;
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

            return redirect()->route('admin.products.index')
                ->with('success', 'Sản phẩm đã có trong đơn hàng → đã ẩn bán (không xóa để giữ lịch sử).');
        }

        $product->load('images');
        foreach ($product->images as $image) {
            $image->deleteFileIfStored();
            $image->delete();
        }
        $product->delete();

        return redirect()->route('admin.products.index')
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
            'expiry_date'      => ($mfgDate && $category->shelf_days)
                                    ? $mfgDate->copy()->addDays($category->shelf_days)->toDateString()
                                    : null,
            'description'      => $data['description'] ?? null,
            'is_featured'      => $data['is_featured'] == '1',
            'is_active'        => $data['is_active'] != '0',
            'delivery_mode'    => !empty($data['delivery_mode']) ? trim($data['delivery_mode']) : 'both',
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

    // public function importStore(Request $request)
    // {
    //     $request->validate([
    //         'file' => 'required|file|mimes:csv,txt|max:2048',
    //     ], [
    //         'file.required' => 'Vui lòng chọn file CSV.',
    //         'file.mimes' => 'Chỉ chấp nhận file .csv',
    //     ]);

    //     $file = $request->file('file');
    //     $handle = fopen($file->getRealPath(), 'r');
    //     if (!$handle) {
    //         return back()->with('error', 'Không đọc được file.');
    //     }

    //     // Bỏ BOM
    //     $first = fgets($handle);
    //     if ($first === false) {
    //         fclose($handle);
    //         return back()->with('error', 'File trống.');
    //     }
    //     $first = preg_replace('/^\xEF\xBB\xBF/', '', $first);
    //     $header = str_getcsv($first);
    //     $header = array_map(fn($h) => trim(mb_strtolower($h)), $header);

    //     $required = ['name', 'category_slug', 'price', 'quantity', 'unit'];
    //     foreach ($required as $col) {
    //         if (!in_array($col, $header, true)) {
    //             fclose($handle);
    //             return back()->with('error', "Thiếu cột bắt buộc: {$col}");
    //         }
    //     }

    //     $created = 0;
    //     $errors = [];
    //     $rowNum = 1;

    //     while (($row = fgetcsv($handle)) !== false) {
    //         $rowNum++;
    //         if (count(array_filter($row, fn($v) => $v !== null && $v !== '')) === 0) {
    //             continue;
    //         }

    //         $data = [];
    //         foreach ($header as $i => $key) {
    //             $data[$key] = $row[$i] ?? null;
    //         }

    //         try {
    //             $category = Category::where('slug', trim($data['category_slug']))->first();
    //             if (!$category) {
    //                 $errors[] = "Dòng {$rowNum}: không tìm thấy category_slug \"{$data['category_slug']}\"";
    //                 continue;
    //             }

    //             $name = trim($data['name'] ?? '');
    //             if ($name === '') {
    //                 $errors[] = "Dòng {$rowNum}: thiếu name";
    //                 continue;
    //             }

    //             $deliveryMode = trim($data['delivery_mode'] ?? 'both') ?: 'both';
    //             if (!in_array($deliveryMode, ['standard', 'express', 'both'], true)) {
    //                 $deliveryMode = 'both';
    //             }

    //             $product = Product::create([
    //                 'category_id' => $category->id,
    //                 'name' => $name,
    //                 'slug' => $this->uniqueSlug($name),
    //                 'description' => $data['description'] ?? null,
    //                 'price' => (float) ($data['price'] ?? 0),
    //                 'sale_price' => ($data['sale_price'] ?? '') !== '' ? (float) $data['sale_price'] : null,
    //                 'quantity' => (int) ($data['quantity'] ?? 0),
    //                 'unit' => trim($data['unit'] ?? 'sp') ?: 'sp',
    //                 'is_featured' => in_array(($data['is_featured'] ?? '0'), ['1', 'true', 'yes'], true),
    //                 'is_active' => !in_array(($data['is_active'] ?? '1'), ['0', 'false', 'no'], true),
    //                 'delivery_mode' => $deliveryMode,
    //             ]);

    //             if (!empty(trim($data['image_url'] ?? ''))) {
    //                 ProductImage::create([
    //                     'product_id' => $product->id,
    //                     'image_url' => trim($data['image_url']),
    //                     'is_primary' => true,
    //                 ]);
    //             }

    //             $created++;
    //         } catch (\Throwable $e) {
    //             $errors[] = "Dòng {$rowNum}: " . $e->getMessage();
    //         }
    //     }

    //     fclose($handle);

    //     $msg = "Import thành công {$created} sản phẩm.";
    //     if (count($errors)) {
    //         $msg .= ' Lỗi: ' . implode(' | ', array_slice($errors, 0, 5));
    //         if (count($errors) > 5) {
    //             $msg .= ' ...';
    //         }
    //     }

    //     return redirect()->route('admin.products.index')
    //         ->with($created > 0 ? 'success' : 'error', $msg);
    // }

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
            $path = $file->store('products','public');
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
