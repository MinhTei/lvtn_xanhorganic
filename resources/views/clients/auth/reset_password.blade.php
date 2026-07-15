@extends('layouts.client')

@section('title', 'Đặt Lại Mật Khẩu')
@section('breadcrumb', 'Đặt lại mật khẩu')

@section('content')

<div class="auth-container">
    <div class="auth-form-wrapper">
        {{-- @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif --}}

        <form id="reset-password-form" method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <input type="hidden" name="email" value="{{ $email ?? old('email') }}">
            <div class="form-group">
                <label for="password">Mật khẩu mới</label>
                <input type="password" id="password" name="password" placeholder="Ít nhất 6 ký tự" autocomplete="new-password">
                @error('password')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password_confirmation">Xác nhận mật khẩu</label>
                <input type="password" id="password_confirmation" name="password_confirmation" placeholder="Nhập lại mật khẩu mới" autocomplete="new-password">
                @error('password_confirmation')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn-submit">Đổi mật khẩu</button>

            <div class="auth-footer">
                Nhớ mật khẩu rồi? <a href="{{ route('login') }}">Đăng nhập</a>
            </div>
        </form>
    </div>
</div>

@endsection
