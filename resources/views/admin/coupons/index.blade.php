@extends('layouts.admin')
@section('title', 'Mã giảm giá')

@section('content')
<div class="panel">
    <div class="panel__head">
        <h2>Coupon</h2>
        <a href="{{ route('admin.coupons.create') }}" class="btn-admin"><i class="fa fa-plus"></i> Thêm mã</a>
    </div>

    <div class="table-responsive">
        <table class="table-admin">
            <thead>
                <tr>
                    <th>Mã</th>
                    <th>Loại</th>
                    <th>Giá trị</th>
                    <th>Đơn tối thiểu</th>
                    <th>Thời gian</th>
                    <th>Đã dùng</th>
                    <th>Mỗi tài khoản</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($coupons as $coupon)
                    <tr>
                        <td><strong>{{ $coupon->code }}</strong></td>
                        <td>{{ $coupon->discount_type === 'percentage' ? '%' : 'Cố định' }}</td>
                        <td>
                            @if($coupon->discount_type === 'percentage')
                                {{ $coupon->discount_value }}%
                            @else
                                {{ number_format($coupon->discount_value, 0, ',', '.') }}₫
                            @endif
                        </td>
                        <td>{{ number_format($coupon->min_order_value, 0, ',', '.') }}₫</td>
                        <td style="font-size:.85rem;">
                            {{ \Carbon\Carbon::parse($coupon->start_date)->format('d/m/Y') }}
                            →
                            {{ \Carbon\Carbon::parse($coupon->end_date)->format('d/m/Y') }}
                        </td>
                        <td>{{ $coupon->usages_count }} / {{ $coupon->usage_limit ? $coupon->usage_limit : 'Vô hạn' }}</td>
                        <td>{{ $coupon->usage_limit_per_user }}</td>
                        <td class="text-nowrap">
                            <a href="{{ route('admin.coupons.edit', $coupon) }}" class="btn-admin btn-admin-outline btn-sm-admin">Sửa</a>
                            <form action="{{ route('admin.coupons.destroy', $coupon) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Xóa mã này?');">
                                @csrf @method('DELETE')
                                <button class="btn-admin btn-admin-danger btn-sm-admin" type="submit">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="7" class="text-muted">Chưa có mã giảm giá.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $coupons->links() }}</div>
</div>
@endsection
