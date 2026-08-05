@php $p = $product ?? null; @endphp
<div class="row">
    <div class="col-md-8">
        <div class="form-group">
            <label>Tên sản phẩm *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name', $p->name ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Danh mục *</label>
            <select name="category_id" class="form-select" required>
                <option value="">— Chọn —</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" @selected(old('category_id', $p->category_id ?? '') == $cat->id)>
                        {{ $cat->parent_id ? '— ' : '' }}{{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>
    </div>
</div>
<div class="row">
    <!-- Cột trái: Mô tả chiếm 8/12 -->
    <div class="col-md-8">
        <div class="form-group">
            <label>Mô tả</label>
            <textarea name="description" class="form-control" rows="2">{{ old('description', $p->description ?? '') }}</textarea>
        </div>
    </div>
    <!-- Cột phải: Ngày SX chiếm 4/12 -->
    <div class="col-md-4">
        <div class="form-group">
            <label>Ngày sản xuất <span class="text-danger">*</span></label>
            <input type="date" name="manufacture_date" class="form-control" 
                   value="{{ old('manufacture_date', isset($p) && $p->manufacture_date ? $p->manufacture_date->format('Y-m-d') : '') }}" required />
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-3">
        <div class="form-group">
            <label>Giá *</label>
            <input type="number" name="price" class="form-control" min="0" step="1000"
                   value="{{ old('price', $p->price ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>Giá KM</label>
            <input type="number" name="sale_price" class="form-control" min="0" step="1000"
                   value="{{ old('sale_price', $p->sale_price ?? '') }}">
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>Tồn kho *</label>
            <input type="number" name="quantity" class="form-control" min="0"
                   value="{{ old('quantity', $p->quantity ?? 0) }}" required>
        </div>
    </div>
    <div class="col-md-3">
        <div class="form-group">
            <label>Đơn vị *</label>
            <input type="text" name="unit" class="form-control"
                   value="{{ old('unit', $p->unit ?? 'kg') }}" required>
        </div>
    </div>
</div>



<div class="form-group">
    <label>Ảnh sản phẩm</label>
    <input type="file" name="images[]" class="form-control" accept="image/jpeg,image/png,image/jpg,image/webp,image/gif"
        multiple>
    <small class="text-muted">Có thể chọn nhiều ảnh (JPG, PNG, WEBP). Ảnh đầu tiên sẽ là ảnh chính nếu chưa có
        ảnh.</small>

    @if(!empty($p) && $p->images->isNotEmpty())
        <div class="d-flex flex-wrap gap-2 mt-2" id="product-existing-images">
            @foreach($p->images as $img)
                <div class="position-relative product-img-thumb" style="width:100px;">
                    <img src="{{ $img->display_url }}" alt="" class="rounded border d-block"
                        style="width:100px;height:100px;object-fit:cover;">
                    <button type="button"
                        class="btn btn-sm btn-danger position-absolute top-0 end-0 m-1 rounded-circle p-0 product-img-remove"
                        style="width:22px;height:22px;line-height:20px;font-size:14px;" data-id="{{ $img->id }}" title="Xóa ảnh"
                        aria-label="Xóa ảnh">&times;</button>
                    @if($img->is_primary)
                        <span class="badge bg-success position-absolute bottom-0 start-0 m-1" style="font-size:10px;">Chính</span>
                    @endif
                </div>
            @endforeach
        </div>
        <div id="product-delete-images"></div>
        <script>
            document.querySelectorAll('.product-img-remove').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var wrap = btn.closest('.product-img-thumb');
                    var id = btn.getAttribute('data-id');
                    var holder = document.getElementById('product-delete-images');
                    if (holder && id) {
                        var input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'delete_images[]';
                        input.value = id;
                        holder.appendChild(input);
                    }
                    if (wrap) wrap.remove();
                });
            });
        </script>
    @endif
</div>

<div class="form-group">
    <label>Loại sản phẩm / giao hàng *</label>
    <select name="delivery_mode" class="form-select" required>
        @php $mode = old('delivery_mode', $p->delivery_mode ?? 'both'); @endphp
        <option value="both" @selected($mode === 'both')>Hàng tươi sống (giao theo khung giờ)</option>
        <option value="standard" @selected($mode === 'standard')>Hàng khô / lưu kho (giao 3–5 ngày)</option>
        <option value="express" @selected($mode === 'express')>Chỉ giao nhanh trong 2 giờ</option>
    </select>
    <small class="text-muted">
        Hàng tươi: khách chọn Hôm nay / Ngày mai + khung giờ (cutoff 20h).
    </small>
</div>

<div class="d-flex gap-4">
    <div class="form-check">
        <input type="checkbox" name="is_featured" value="1" class="form-check-input" id="is_featured"
            @checked(old('is_featured', $p->is_featured ?? false))>
        <label class="form-check-label" for="is_featured">Nổi bật</label>
    </div>
    <div class="form-check">
        <input type="hidden" name="is_active" value="0">
        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active"
            @checked(old('is_active', $p->is_active ?? true))>
        <label class="form-check-label" for="is_active">Đang bán</label>
    </div>
</div>