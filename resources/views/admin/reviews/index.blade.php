@extends('layouts.admin')
@section('title', 'Quản lý đánh giá')

@section('content')
<div class="panel">
    <div class="panel__head">
        <h2>Đánh giá sản phẩm</h2>
    </div>

    <form class="filters" method="GET">
        <select name="visible" class="form-select">
            <option value="">Tất cả</option>
            <option value="1" @selected(request('visible') === '1')>Đang hiện</option>
            <option value="0" @selected(request('visible') === '0')>Đang ẩn</option>
        </select>
        <select name="rating" class="form-select">
            <option value="">Số sao</option>
            @for($i = 5; $i >= 1; $i--)
                <option value="{{ $i }}" @selected(request('rating') == $i)>{{ $i }} sao</option>
            @endfor
        </select>
        <button class="btn-admin btn-sm-admin" type="submit">Lọc</button>
    </form>

    <div class="table-responsive">
        <table class="table-admin">
            <thead>
                <tr>
                    <th>SP</th>
                    <th>Khách</th>
                    <th>Sao</th>
                    <th>Nội dung</th>
                    <th>TT</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($reviews as $review)
                    <tr>
                        <td>{{ $review->product->name ?? '—' }}</td>
                        <td>{{ $review->user->name ?? '—' }}</td>
                        <td>{{ $review->rating }}/5</td>
                        <td style="max-width:280px;">{{ Str::limit($review->comment, 80) ?: '—' }}</td>
                        <td>
                            @if($review->is_visible)
                                <span class="badge-soft">Hiện</span>
                            @else
                                <span class="badge-muted">Ẩn</span>
                            @endif
                        </td>
                        <td class="text-nowrap">
                            <form action="{{ route('admin.reviews.toggle', $review) }}" method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                <button class="btn-admin btn-admin-outline btn-sm-admin" type="submit">
                                    {{ $review->is_visible ? 'Ẩn' : 'Hiện' }}
                                </button>
                            </form>
                            <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="d-inline"
                                  onsubmit="return confirm('Xóa đánh giá?');">
                                @csrf @method('DELETE')
                                <button class="btn-admin btn-admin-danger btn-sm-admin" type="submit">Xóa</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-muted">Chưa có đánh giá.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $reviews->links() }}</div>
</div>
@endsection
