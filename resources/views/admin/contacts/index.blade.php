@extends('layouts.admin')
@section('title', 'Quản lý liên hệ')

@section('content')
<div class="panel">
    <div class="panel__head">
        <h2>Tin nhắn liên hệ</h2>
    </div>

    <form class="filters" method="GET">
        <input type="text" name="q" class="form-control" placeholder="Tên / email / SĐT" value="{{ request('q') }}">
        <select name="replied" class="form-select">
            <option value="">Tất cả</option>
            <option value="0" @selected(request('replied') === '0')>Chưa phản hồi</option>
            <option value="1" @selected(request('replied') === '1')>Đã phản hồi</option>
        </select>
        <button class="btn-admin btn-sm-admin" type="submit">Lọc</button>
    </form>

    <div class="table-responsive">
        <table class="table-admin">
            <thead>
                <tr>
                    <th>Người gửi</th>
                    <th>Liên hệ</th>
                    <th>Nội dung</th>
                    <th>TT</th>
                    <th>Ngày</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse($contacts as $contact)
                    <tr>
                        <td><strong>{{ $contact->name }}</strong></td>
                        <td style="font-size:.85rem;">{{ $contact->email }}<br>{{ $contact->phone }}</td>
                        <td>{{ Str::limit($contact->message, 60) }}</td>
                        <td>
                            @if($contact->is_replied)
                                <span class="badge-soft">Đã trả lời</span>
                            @else
                                <span class="badge-warn">Mới</span>
                            @endif
                        </td>
                        <td>{{ $contact->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.contacts.show', $contact) }}" class="btn-admin btn-admin-outline btn-sm-admin">Xem</a>
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="6" class="text-muted">Chưa có liên hệ.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>
    <div class="mt-3">{{ $contacts->links() }}</div>
</div>
@endsection
