<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class AdminCategoryController extends Controller
{
    public function index()
    {
        $categories = Category::with('parent')
            ->withCount('products')
            ->orderByRaw('parent_id IS NOT NULL')
            ->orderBy('name')
            ->paginate(20);

        return view('admin.categories.index', compact('categories'));
    }

    public function create()
    {
        $parents = Category::whereNull('parent_id')->orderBy('name')->get();

        return view('admin.categories.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        $data['slug'] = $this->uniqueSlug($data['name']);

        Category::create($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Thêm danh mục thành công.');
    }

    public function edit(Category $category)
    {
        $parents = Category::whereNull('parent_id')
            ->where('id', '!=', $category->id)
            ->orderBy('name')
            ->get();

        return view('admin.categories.edit', compact('category', 'parents'));
    }

    public function update(Request $request, Category $category)
    {
        $data = $this->validated($request, $category->id);

        if ($category->name !== $data['name']) {
            $data['slug'] = $this->uniqueSlug($data['name'], $category->id);
        }

        // Không cho chọn chính nó / con của nó làm parent
        if (!empty($data['parent_id']) && (int) $data['parent_id'] === $category->id) {
            return back()->with('error', 'Danh mục không thể là cha của chính nó.');
        }

        $category->update($data);

        return redirect()->route('admin.categories.index')
            ->with('success', 'Cập nhật danh mục thành công.');
    }

    public function destroy(Category $category)
    {
        if ($category->products()->exists()) {
            return back()->with('error', 'Không thể xóa danh mục còn sản phẩm.');
        }

        if ($category->children()->exists()) {
            return back()->with('error', 'Không thể xóa danh mục còn danh mục con.');
        }

        $category->delete();

        return redirect()->route('admin.categories.index')
            ->with('success', 'Đã xóa danh mục.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('categories', 'name')->ignore($ignoreId),
            ],
            'parent_id' => 'nullable|exists:categories,id',
            'is_active' => 'nullable|boolean',
        ], [
            'name.required' => 'Vui lòng nhập tên danh mục.',
            'name.unique' => 'Tên danh mục đã tồn tại.',
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $data['parent_id'] = $data['parent_id'] ?: null;

        return $data;
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'danh-muc';
        $slug = $base;
        $i = 1;

        while (
            Category::where('slug', $slug)
                ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
                ->exists()
        ) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }
}
