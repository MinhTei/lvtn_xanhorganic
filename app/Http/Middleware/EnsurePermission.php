<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Kiểm tra quyền: middleware('permission:manage_products')
 */
class EnsurePermission
{
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = $request->user();

        if (!$user || !$user->hasPermission($permission)) {
            abort(403, 'Bạn không có quyền thực hiện chức năng này.');
        }

        return $next($request);
    }
}
