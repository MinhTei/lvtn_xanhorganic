<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Chỉ cho phép tài khoản admin / staff (status = active) vào /admin.
 * Chưa login → đẩy về /login (form chung), nhớ URL intended.
 */
class EnsureAdmin
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::check()) {
            return redirect()
                ->guest(route('login'))
                ->with('error', 'Vui lòng đăng nhập để vào trang quản trị.');
        }

        $user = Auth::user();

        if (!$user->canAccessAdmin()) {
            // Customer vào /admin → về home, KHÔNG logout
            return redirect()
                ->route('home')
                ->with('error', 'Bạn không có quyền truy cập trang quản trị.');
        }

        return $next($request);
    }
}
