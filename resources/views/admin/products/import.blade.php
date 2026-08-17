@extends('layouts.admin')
@section('title', 'Import sản phẩm')

@section('content')
<div class="card" style="max-width:640px;">
    <div class="card-header bg-white">Import sản phẩm từ CSV</div>
    <div class="card-body">
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
