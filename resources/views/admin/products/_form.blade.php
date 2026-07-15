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

<div class="form-group">
    <label>Mô tả</label>
    <textarea name="description" class="form-control" rows="4">{{ old('description', $p->description ?? '') }}</textarea>
</div>

<div class="row">
    <div class="col-md-4">
        <div class="form-group">
            <label>Giá *</label>
            <input type="number" name="price" class="form-control" min="0" step="1000"
                   value="{{ old('price', $p->price ?? '') }}" required>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group">
            <label>Giá KM</label>
            <input type="number" name="sale_price" class="form-control" min="0" step="1000"
                   value="{{ old('sale_price', $p->sale_price ?? '') }}">
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label>Tồn kho *</label>
            <input type="number" name="quantity" class="form-control" min="0"
                   value="{{ old('quantity', $p->quantity ?? 0) }}" required>
        </div>
    </div>
    <div class="col-md-2">
        <div class="form-group">
            <label>Đơn vị *</label>
            <input type="text" name="unit" class="form-control"
                   value="{{ old('unit', $p->unit ?? 'kg') }}" required>
        </div>
    </div>
</div>

<div class="form-group">
    <label>URL ảnh chính</label>
    <input type="text" name="image_url" class="form-control"
           value="{{ old('image_url', $primaryImage ?? '') }}"
           placeholder="https://... hoặc /assets/clients/img/...">
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
