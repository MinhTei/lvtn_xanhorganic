@extends('layouts.admin')
@section('title', 'Chi tiết liên hệ')

@section('content')
<div class="panel" style="max-width:680px;">
    <div class="panel__head">
        <h2>Liên hệ #{{ $contact->id }}</h2>
        <a href="{{ route('admin.contacts.index') }}" class="btn-admin btn-admin-outline btn-sm-admin">Quay lại</a>
    </div>

    <div class="mb-3">
        <div class="text-muted" style="font-size:.8rem;">Người gửi</div>
        <strong>{{ $contact->name }}</strong>
    </div>
    <div class="mb-3">
        <div class="text-muted" style="font-size:.8rem;">Email / SĐT</div>
        {{ $contact->email }} · {{ $contact->phone }}
    </div>
    <div class="mb-3">
        <div class="text-muted" style="font-size:.8rem;">Ngày gửi</div>
        {{ $contact->created_at->format('d/m/Y H:i') }}
    </div>
    <div class="mb-4">
        <div class="text-muted" style="font-size:.8rem;">Nội dung</div>
        <div class="p-3 rounded" style="background:#f8faf8; white-space:pre-wrap;">{{ $contact->message }}</div>
    </div>

    <div class="d-flex gap-2 flex-wrap">
        @if(!$contact->is_replied)
            <form action="{{ route('admin.contacts.reply', $contact) }}" method="POST">
                @csrf @method('PATCH')
                <button type="submit" class="btn-admin">Đánh dấu đã phản hồi</button>
            </form>
        @else
            <span class="badge-soft align-self-center">Đã phản hồi</span>
        @endif

        <a href="mailto:{{ $contact->email }}" class="btn-admin btn-admin-outline">Gửi email trả lời</a>

        <form action="{{ route('admin.contacts.destroy', $contact) }}" method="POST"
              onsubmit="return confirm('Xóa liên hệ này?');">
            @csrf @method('DELETE')
            <button type="submit" class="btn-admin btn-admin-danger">Xóa</button>
        </form>
    </div>
</div>
@endsection
