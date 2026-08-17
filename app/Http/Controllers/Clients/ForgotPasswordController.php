<?php
namespace App\Http\Controllers\Clients;
use App\Http\Controllers\Controller;
use App\Models\PasswordResetToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    public function showForgotPasswordForm(){
        return view('clients.auth.forgot_password');
    }
    public function sendResetLinkEmail(Request $request){
        $request->validate([
            'email'=> 'required|email|exists:users,email',
        ],
        [
            'email.required' => 'Bắt buộc nhập Email',
            'email.email'=> 'Không hợp lệ',
            'email.exists' => 'Email chưa được đăng ký'
        ]);

        $status = Password::sendResetLink($request->only('email'));
        if($status===Password::RESET_LINK_SENT)
        {
            return back()->with('success','Đã gửi liên kết đặt lại mật khẩu qua email');
        }
        return back()->with('error','Không thể gửi liên kết. Vui lòng thử lại')->withErrors('email',__($status));
    }
}
