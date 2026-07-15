<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Illuminate\Support\Facades\Route::middleware('web')
                ->prefix('admin')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'auth.custom' => \App\Http\Middleware\RedirectIfNotAuthenticared::class,
            'admin' => \App\Http\Middleware\EnsureAdmin::class,
            'permission' => \App\Http\Middleware\EnsurePermission::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'vnpay/ipn',
        ]);

        // Guest middleware: user đã login vào /login|/register → về đúng khu vực theo role
        $middleware->redirectUsersTo(function () {
            $user = auth()->user();
            if ($user && method_exists($user, 'canAccessAdmin') && $user->canAccessAdmin()) {
                return route('admin.dashboard');
            }

            return route('home');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->shouldRenderJsonWhen(
            fn (Request $request) => $request->is('api/*'),
        );
    })->create();
