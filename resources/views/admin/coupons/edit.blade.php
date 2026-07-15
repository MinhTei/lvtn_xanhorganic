@extends('layouts.admin')
@section('title', isset($coupon) ? 'Sửa coupon' : 'Thêm coupon')

@section('content')
<div class="panel" style="max-width:560px;">
    <div class="panel__head">
        <h2>{{ isset($coupon) ? 'Sửa mã giảm giá' : 'Thêm mã giảm giá' }}</h2>
    </div>
    <form method="POST"
          action="{{ isset($coupon) ? route('admin.coupons.update', $coupon) : route('admin.coupons.store') }}"
          class="form-admin">
        @csrf
        @if(isset($coupon)) @method('PUT') @endif

        <div class="form-group">
            <label>Mã *</label>
            <input type="text" name="code" class="form-control text-uppercase"
                   value="{{ old('code', $coupon->code ?? '') }}" required>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Loại *</label>
                    <select name="discount_type" class="form-select" required>
                        <option value="percentage" @selected(old('discount_type', $coupon->discount_type ?? '') === 'percentage')>Phần trăm (%)</option>
                        <option value="fixed" @selected(old('discount_type', $coupon->discount_type ?? '') === 'fixed')>Cố định (₫)</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Giá trị *</label>
                    <input type="number" name="discount_value" class="form-control" min="0" step="0.01"
                           value="{{ old('discount_value', $coupon->discount_value ?? '') }}" required>
                </div>
            </div>
        </div>
        <div class="form-group">
            <label>Đơn tối thiểu (₫)</label>
            <input type="number" name="min_order_value" class="form-control" min="0"
                   value="{{ old('min_order_value', $coupon->min_order_value ?? 0) }}">
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label>Bắt đầu *</label>
                    <input type="date" name="start_date" class="form-control"
                           value="{{ old('start_date', isset($coupon) ? \Carbon\Carbon::parse($coupon->start_date)->format('Y-m-d') : '') }}" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label>Kết thúc *</label>
                    <input type="date" name="end_date" class="form-control"
                           value="{{ old('end_date', isset($coupon) ? \Carbon\Carbon::parse($coupon->end_date)->format('Y-m-d') : '') }}" required>
                </div>
            </div>
        </div>

        <div class="d-flex gap-2">
            <button type="submit" class="btn-admin">Lưu</button>
            <a href="{{ route('admin.coupons.index') }}" class="btn-admin btn-admin-outline">Hủy</a>
        </div>
    </form>
</div>
@endsection
