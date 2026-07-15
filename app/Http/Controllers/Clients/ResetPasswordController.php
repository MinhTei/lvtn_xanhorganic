<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;

class ResetPasswordController extends Controller
{
    public function showResetPasswordForm(Request $request, $token)
    {
        $email = $request->email;
        return view('clients.auth.reset_password', compact('token', 'email'));
    }
    public function resetPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email|exists:users,email',
            'password' => 'required|min:6|confirmed'

        ], [
            'token.required' => 'Token không hợp lệ hoạc đã hết hạn',
            'email.required' => 'Email không được để trống',
            'email.exists' => 'Email chưa được đăng ký',
            'email.email' => 'Email không hợp lệ',
            'password.min' => 'Mật khẩu ít nhất 6 ký tự',
            'password.required' => 'Mật khẩu không được để trống',
            'password.confirmed' => 'Xác nhận mật khẩu không khớp',
        ]);

        $status = Password::reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->forceFill([
                    'password' => Hash::make($password)
                ])->save();
            }
        );
        if ($status === Password::PASSWORD_RESET) {
            return redirect()->route('login')->with('success', 'Đặt lại mật khẩu thành công. Hãy đăng nhập');
        }
        return back()->with('error', 'Không thể đặt lại mật khẩu. Vui lòng thử lại')->withErrors(['email' => __($status)]);
    }
}
