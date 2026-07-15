@extends('layouts.admin')
@section('title', 'Import sản phẩm')

@section('content')
<div class="card" style="max-width:640px;">
    <div class="card-header bg-white">Import sản phẩm từ CSV</div>
    <div class="card-body">
        <p class="small text-muted">
            Cột bắt buộc: <code>name, category_slug, price, quantity, unit</code><br>
            Tuỳ chọn: <code>sale_price, description, is_featured, is_active, delivery_mode, image_url</code><br>
            <code>delivery_mode</code>:
            <code>both</code> = hàng tươi (khung giờ) |
            <code>standard</code> = hàng khô 3–5 ngày |
            <code>express</code> = chỉ giao nhanh 2h
        </p>

        <a href="{{ route('admin.products.import.template') }}" class="btn btn-sm btn-outline-secondary mb-3">
            Tải file mẫu CSV
        </a>

        <form method="POST" action="{{ route('admin.products.import.store') }}" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Chọn file .csv</label>
                <input type="file" name="file" class="form-control" accept=".csv,text/csv" required>
            </div>
            <button type="submit" class="btn btn-primary">Import</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">Huỷ</a>
        </form>
    </div>
</div>
@endsection
