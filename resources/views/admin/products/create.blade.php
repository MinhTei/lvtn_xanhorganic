@extends('layouts.admin')
@section('title', 'Thêm sản phẩm')

@section('content')
<div class="panel" style="max-width:780px;">
    <div class="panel__head"><h2>Thêm sản phẩm mới</h2></div>
    <form method="POST" action="{{ route('admin.products.store') }}" class="form-admin" enctype="multipart/form-data">
        @csrf
        @include('admin.products._form')
        <div class="d-flex gap-2 mt-3">
            <button type="submit" class="btn-admin">Lưu</button>
            <a href="{{ route('admin.products.index') }}" class="btn-admin btn-admin-outline">Hủy</a>
        </div>
    </form>
</div>
@endsection
