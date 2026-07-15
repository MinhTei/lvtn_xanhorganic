@extends('layouts.client')

@section('title', 'Đăng nhập')

@section('breadcrumb','Đăng nhập')

@section('content')


<div class="auth-container">
    <div class="auth-form-wrapper">
        <form id="login-form" method="POST" action="{{ route('login.post') }}" novalidate >
            @csrf

            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Nhập email của bạn" >
                @error('email')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" placeholder="Nhập mật khẩu" >
                @error('password')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-checkbox">
                <label>
                    <input type="checkbox" name="remember"> Ghi nhớ đăng nhập
                </label>
                <a href="{{ route('forgot.password') }}">Quên mật khẩu?</a>
            </div>

            <button type="submit" class="btn-submit">Đăng Nhập</button>

            <div class="auth-footer">
                Chưa có tài khoản? <a href="{{ route('register') }}">Đăng ký ngay</a>
            </div>
        </form>
    </div>
</div>

@endsection