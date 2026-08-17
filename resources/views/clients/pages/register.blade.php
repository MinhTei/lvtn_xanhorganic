@extends('layouts.client')
@section('title', 'Đăng ký')
@section('breadcrumb', 'Đăng ký')
@section('content')

<div class="auth-container">
    <div class="aurapth-form-wper">
        <form id="register-form" method="POST" action="{{ route('register.post') }}" novalidate>
            @csrf

            <div class="form-group">
                <label for="name">Họ và tên</label>
            <input type="text" id="name" name="name" placeholder="Nhập họ và tên"
                       value="{{ old('name') }}">
                @error('name')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="email">Email</label>
            <input type="email" id="email" name="email" placeholder="Nhập email"
                       value="{{ old('email') }}">
                @error('email')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="phone">Số điện thoại </label>
            <input type="tel" id="phone" name="phone" placeholder="Vui nhập số điện thoại"
                       value="{{ old('phone') }}">
                @error('phone')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="password">Mật khẩu</label>
            <input type="password" id="password" name="password" placeholder="Ít nhất 6 ký tự">
                @error('password')
                    <span class="error-text">{{ $message }}</span>
                @enderror
            </div>

            <div class="form-group">
                <label for="comfirmPassword">Xác nhận mật khẩu</label>
            <input type="password" id="comfirmPassword" name="comfirmPassword"placeholder="Nhập lại mật khẩu">
                @error('comfirmPassword')
                    <span class='error-text'>{{ $message }}</span>
                @enderror
            </div>


            <div class="form-checkbox">
                <label>
                    <input type="checkbox" name="checkbox">
                    Tôi đã đọc và đồng ý với <a href="#">Điều khoản sử dụng</a> và <a href="#">Chính sách bảo mật</a>
                </label>
            </div>

            <button type="submit" class="btn-submit">Tạo Tài Khoản</button>

            <div class="auth-footer">
                Đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a>
            </div>
        </form>
    </div>
</div>

@endsection
