<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

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
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();

        return view('admin.products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['name']);

        $product = Product::create($data);

        if ($request->filled('image_url')) {
            ProductImage::create([
                'product_id' => $product->id,
                'image_url' => $request->image_url,
                'is_primary' => true,
            ]);
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Thêm sản phẩm thành công.');
    }

    public function edit(Product $product)
    {
        $product->load('images');
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $primaryImage = $product->images->where('is_primary', 1)->first()?->image_url;

        return view('admin.products.edit', compact('product', 'categories', 'primaryImage'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validated($request, $product->id);

        if ($product->name !== $data['name']) {
            $data['slug'] = $this->uniqueSlug($data['name'], $product->id);
        }

        $product->update($data);

        if ($request->filled('image_url')) {
            $image = $product->images()->where('is_primary', true)->first();
            if ($image) {
                $image->update(['image_url' => $request->image_url]);
            } else {
                ProductImage::create([
                    'product_id' => $product->id,
                    'image_url' => $request->image_url,
                    'is_primary' => true,
                ]);
            }
        }

        return redirect()->route('admin.products.index')
            ->with('success', 'Cập nhật sản phẩm thành công.');
    }

    public function destroy(Product $product)
    {
        // Đã từng bán → chỉ ẩn, giữ lịch sử đơn
        if ($product->orderItems()->exists()) {
            $product->update(['is_active' => false]);

            return redirect()->route('admin.products.index')
                ->with('success', 'Sản phẩm đã có trong đơn hàng → đã ẩn bán (không xóa để giữ lịch sử).');
        }

        $product->images()->delete();
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Đã xóa sản phẩm.');
    }

    /** Form import CSV */
    public function importForm()
    {
        return view('admin.products.import');
    }

    /** Tải file CSV mẫu */
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
                'name', 'category_slug', 'price', 'sale_price', 'quantity', 'unit',
                'description', 'is_featured', 'is_active', 'delivery_mode', 'image_url',
            ]);
            fputcsv($out, [
                'Rau cải hữu cơ', 'rau-cu', '35000', '30000', '100', 'kg',
                'Rau sạch', '0', '1', 'both', '',
            ]);
            fclose($out);
        };

        return response()->stream($callback, 200, $headers);
    }

    /** Xử lý import CSV */
    public function importStore(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:csv,txt|max:2048',
        ], [
            'file.required' => 'Vui lòng chọn file CSV.',
            'file.mimes' => 'Chỉ chấp nhận file .csv',
        ]);

        $file = $request->file('file');
        $handle = fopen($file->getRealPath(), 'r');
        if (!$handle) {
            return back()->with('error', 'Không đọc được file.');
        }

        // Bỏ BOM
        $first = fgets($handle);
        if ($first === false) {
            fclose($handle);
            return back()->with('error', 'File trống.');
        }
        $first = preg_replace('/^\xEF\xBB\xBF/', '', $first);
        $header = str_getcsv($first);
        $header = array_map(fn ($h) => trim(mb_strtolower($h)), $header);

        $required = ['name', 'category_slug', 'price', 'quantity', 'unit'];
        foreach ($required as $col) {
            if (!in_array($col, $header, true)) {
                fclose($handle);
                return back()->with('error', "Thiếu cột bắt buộc: {$col}");
            }
        }

        $created = 0;
        $errors = [];
        $rowNum = 1;

        while (($row = fgetcsv($handle)) !== false) {
            $rowNum++;
            if (count(array_filter($row, fn ($v) => $v !== null && $v !== '')) === 0) {
                continue;
            }

            $data = [];
            foreach ($header as $i => $key) {
                $data[$key] = $row[$i] ?? null;
            }

            try {
                $category = Category::where('slug', trim($data['category_slug']))->first();
                if (!$category) {
                    $errors[] = "Dòng {$rowNum}: không tìm thấy category_slug \"{$data['category_slug']}\"";
                    continue;
                }

                $name = trim($data['name'] ?? '');
                if ($name === '') {
                    $errors[] = "Dòng {$rowNum}: thiếu name";
                    continue;
                }

                $deliveryMode = trim($data['delivery_mode'] ?? 'both') ?: 'both';
                if (!in_array($deliveryMode, ['standard', 'express', 'both'], true)) {
                    $deliveryMode = 'both';
                }

                $product = Product::create([
                    'category_id' => $category->id,
                    'name' => $name,
                    'slug' => $this->uniqueSlug($name),
                    'description' => $data['description'] ?? null,
                    'price' => (float) ($data['price'] ?? 0),
                    'sale_price' => ($data['sale_price'] ?? '') !== '' ? (float) $data['sale_price'] : null,
                    'quantity' => (int) ($data['quantity'] ?? 0),
                    'unit' => trim($data['unit'] ?? 'sp') ?: 'sp',
                    'is_featured' => in_array(($data['is_featured'] ?? '0'), ['1', 'true', 'yes'], true),
                    'is_active' => !in_array(($data['is_active'] ?? '1'), ['0', 'false', 'no'], true),
                    'delivery_mode' => $deliveryMode,
                ]);

                if (!empty(trim($data['image_url'] ?? ''))) {
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_url' => trim($data['image_url']),
                        'is_primary' => true,
                    ]);
                }

                $created++;
            } catch (\Throwable $e) {
                $errors[] = "Dòng {$rowNum}: " . $e->getMessage();
            }
        }

        fclose($handle);

        $msg = "Import thành công {$created} sản phẩm.";
        if (count($errors)) {
            $msg .= ' Lỗi: ' . implode(' | ', array_slice($errors, 0, 5));
            if (count($errors) > 5) {
                $msg .= ' ...';
            }
        }

        return redirect()->route('admin.products.index')
            ->with($created > 0 ? 'success' : 'error', $msg);
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
            'is_featured' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
            'image_url' => 'nullable|string|max:500',
        ], [
            'category_id.required' => 'Vui lòng chọn danh mục.',
            'name.required' => 'Vui lòng nhập tên sản phẩm.',
            'price.required' => 'Vui lòng nhập giá.',
            'sale_price.lt' => 'Giá khuyến mãi phải nhỏ hơn giá gốc.',
            'quantity.required' => 'Vui lòng nhập tồn kho.',
            'unit.required' => 'Vui lòng nhập đơn vị.',
            'delivery_mode.required' => 'Vui lòng chọn loại giao hàng.',
        ]);

        $data['is_featured'] = $request->boolean('is_featured');
        $data['is_active'] = $request->boolean('is_active');
        $data['sale_price'] = $data['sale_price'] ?? null;

        return $data;
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'san-pham';
        $slug = $base;
        $i = 1;

        while (
            Product::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
