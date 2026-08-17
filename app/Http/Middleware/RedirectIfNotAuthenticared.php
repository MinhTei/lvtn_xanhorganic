<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * Bắt buộc đăng nhập.
 * Dùng redirect()->guest() để sau login quay lại đúng trang trước
 * (vd: guest vào /checkout → login → quay lại /checkout).
 */
class RedirectIfNotAuthenticared
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!Auth::guard('web')->check()) {
            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vui lòng đăng nhập để tiếp tục.',
                    'login_url' => route('login'),
                ], 401);
            }

            return redirect()
                ->guest(route('login'))
                ->with('error', 'Vui lòng đăng nhập để tiếp tục thanh toán / vào tài khoản.');
        }

        return $next($request);
    }
}
