@extends('layouts.client')

@section('title', 'Quên Mật Khẩu')
@section('breadcrumb', 'Quên Mật Khẩu')

@section('content')


<div class="auth-container">
    <div class="auth-form-wrapper">
        {{-- @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-error">{{ session('error') }}</div>
        @endif --}}
        <form id="forgot-password-form" method="POST" action="{{ route('forgot.password.post') }}">
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Nhập email đã đăng ký" 
                       required value="{{ old('email') ?? '' }}">
                @error('email')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn-submit">Gửi Link Đặt Lại Mật Khẩu</button>

            <div class="auth-footer">
                <a href="{{ route('login') }}">← Quay lại đăng nhập</a>
            </div>

            <div class="help-text">
                Link đặt lại mật khẩu sẽ được gửi đến email của bạn và hết hạn sau 1 giờ
            </div>
        </form>
    </div>
</div>

@endsection
