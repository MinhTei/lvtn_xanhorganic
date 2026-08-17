<?php

namespace App\Http\Controllers\Clients;

use App\Http\Controllers\Controller;
use App\Mail\ActivationMail;
use App\Models\Role;
use App\Models\User;
use App\Services\ClientCart;
use App\Services\ClientWishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;


class AuthController extends Controller
{
    public function showLoginForm()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('clients.pages.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:6',
        ], [
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không hợp lệ',
            'password.required' => 'Mật khẩu không được để trống',
            'password.min' => 'Mật khẩu ít nhất 6 ký tự',
        ]);

        if (!Auth::attempt([
            'email' => $request->email,
            'password' => $request->password,
            'status' => 'active',
        ], $request->boolean('remember'))) {
            return redirect()->route('login')->with(
                'error',
                'Email hoặc mật khẩu không chính xác - Hoặc tài khoản của bạn chưa được kích hoạt!'
            );
        }

        $request->session()->regenerate();
        $user = Auth::user();

        // Admin / Staff → trang quản trị
        if ($user->canAccessAdmin()) {
            return redirect()
                ->intended(route('admin.dashboard'))
                ->with('success', 'Đăng nhập quản trị thành công!');
        }

        // Customer → website
        if ($user->role?->name === 'customer') {
            ClientCart::mergeSessionToUser($user);
            ClientWishlist::mergeSessionToUser($user);

            return redirect()
                ->intended(route('home'))
                ->with('success', 'Đăng nhập thành công!');
        }

        Auth::logout();

        return redirect()->route('login')->with('error', 'Tài khoản không hợp lệ.');
    }

    public function showRegisterForm()
    {
        if (Auth::check()) {
            return $this->redirectByRole(Auth::user());
        }

        return view('clients.pages.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|regex:/^[0-9]{10,12}$/',
            'password' => 'required|string|min:6',
            'comfirmPassword' => 'required|same:password',
        ], [
            'name.required' => 'Họ tên không được để trống',
            'email.required' => 'Email không được để trống',
            'email.email' => 'Email không hợp lệ',
            'phone.required' => 'Số điện thoại không được để trống',
            'phone.regex' => 'Số điện thoại không hợp lệ',
            'password.required' => 'Mật khẩu không được để trống',
            'password.min' => 'Mật khẩu ít nhất 6 ký tự',
            'comfirmPassword.required' => 'Vui lòng xác nhận mật khẩu',
            'comfirmPassword.same' => 'Mật khẩu xác nhận không khớp',
        ]);

        $existingUser = User::where('email', $request->email)->first();

        if ($existingUser) {
            if ($existingUser->isPending()) {
                return redirect()->route('register')->with(
                    'warning',
                    'Tài khoản đã được đăng ký, đang chờ được kích hoạt!'
                );
            }

            return redirect()->route('register')->with('error', 'Email này đã được sử dụng!');
        }

        // Đăng ký công khai chỉ tạo customer (admin/staff do Admin tạo)
        $customerRole = Role::where('name', 'customer')->firstOrFail();
        $token = Str::random(64);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => bcrypt($request->password),
            'status' => 'pending',
            'role_id' => $customerRole->id,
            'activation_token' => $token,
        ]);

        Mail::to($user->email)->send(new ActivationMail($token, $user));

        return redirect()->back()->with(
            'success',
            'Tài khoản đã được đăng ký, chờ được kích hoạt!'
        );
    }

    public function activate($token)
    {
        $user = User::where('activation_token', $token)->first();

        if ($user) {
            $user->status = 'active';
            $user->activation_token = null;
            $user->save();

            return redirect()->route('login')->with(
                'success',
                'Tài khoản đã được kích hoạt. Vui lòng đăng nhập!'
            );
        }

        return redirect()->route('login')->with(
            'error',
            'Liên kết kích hoạt không hợp lệ hoặc đã hết hạn!'
        );
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Đã đăng xuất thành công!');
    }

    private function redirectByRole(User $user)
    {
        if ($user->canAccessAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->route('home');
    }
}
