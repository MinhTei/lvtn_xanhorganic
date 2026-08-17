@extends('layouts.admin')
@section('title', 'Sửa sản phẩm')

@section('content')
<div class="panel" style="max-width:780px;">
    <div class="panel__head"><h2>Sửa: {{ $product->name }}</h2></div>
    <form method="POST" action="{{ route('admin.products.update', $product) }}" class="form-admin" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('admin.products._form')
        <div class="d-flex gap-2 mt-3">
            <button type="submit" class="btn-admin">Cập nhật</button>
            <a href="{{ route('admin.products.index') }}" class="btn-admin btn-admin-outline">Hủy</a>
        </div>
    </form>
</div>
@endsection
