<?php

use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminContactController;
use App\Http\Controllers\Admin\AdminCouponController;
use App\Http\Controllers\Admin\AdminDashboardController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminReviewController;
use App\Http\Controllers\Admin\AdminRoleController;
use App\Http\Controllers\Admin\AdminUserController;
use App\Http\Controllers\Clients\AuthController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes — prefix /admin
|--------------------------------------------------------------------------
|
| KHÔNG có login riêng.
| Đăng nhập chung tại /login → hệ thống đưa admin/staff vào đây.
|
| Middleware:
|   admin      → chỉ admin/staff active
|   permission → kiểm tra quyền manage_*
|
*/

// Bookmark cũ /admin/login → chuyển về form đăng nhập chung
Route::get('/login', fn () => redirect()->route('login'))->name('admin.login');

Route::middleware(['admin'])->group(function () {
    // Logout dùng AuthController chung → về /login
    Route::post('/logout', [AuthController::class, 'logout'])->name('admin.logout');

    Route::get('/', [AdminDashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/dashboard', [AdminDashboardController::class, 'index']);
    Route::get('/dashboard/export', [AdminDashboardController::class, 'export'])->name('admin.dashboard.export');
    Route::get('/dashboard/export-pdf', [AdminDashboardController::class, 'exportPdf'])->name('admin.dashboard.export.pdf');

    // Đơn hàng
    Route::middleware('permission:manage_orders')->group(function () {
        Route::get('orders', [AdminOrderController::class, 'index'])->name('admin.orders.index');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('admin.orders.show');
        Route::get('orders/{order}/invoice', [AdminOrderController::class, 'invoice'])->name('admin.orders.invoice');
        Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('admin.orders.status');
    });

    Route::middleware('permission:manage_products')->group(function () {
        Route::get('products/import', [AdminProductController::class, 'importForm'])->name('admin.products.import');
        Route::get('products/import/template', [AdminProductController::class, 'importTemplate'])->name('admin.products.import.template');
        Route::post('products/import', [AdminProductController::class, 'importStore'])->name('admin.products.import.store');
        Route::resource('products', AdminProductController::class)
            ->except(['show'])
            ->names('admin.products');
    });

    Route::middleware('permission:manage_categories')->group(function () {
        Route::resource('categories', AdminCategoryController::class)
            ->except(['show'])
            ->names('admin.categories');
    });

    Route::middleware('permission:manage_users')->group(function () {
        Route::patch('users/{user}/toggle-block', [AdminUserController::class, 'toggleBlock'])
            ->name('admin.users.toggle-block');
        Route::resource('users', AdminUserController::class)
            ->except(['create', 'store', 'destroy'])
            ->names('admin.users');
        Route::resource('roles', AdminRoleController::class)
            ->except(['show'])
            ->names('admin.roles');
    });

    Route::middleware('permission:manage_coupons')->group(function () {
        Route::resource('coupons', AdminCouponController::class)
            ->except(['show'])
            ->names('admin.coupons');
    });

    Route::middleware('permission:manage_reviews')->group(function () {
        Route::get('reviews', [AdminReviewController::class, 'index'])->name('admin.reviews.index');
        Route::patch('reviews/{review}/toggle', [AdminReviewController::class, 'toggle'])->name('admin.reviews.toggle');
        Route::delete('reviews/{review}', [AdminReviewController::class, 'destroy'])->name('admin.reviews.destroy');
    });

    Route::middleware('permission:manage_contacts')->group(function () {
        Route::get('contacts', [AdminContactController::class, 'index'])->name('admin.contacts.index');
        Route::get('contacts/{contact}', [AdminContactController::class, 'show'])->name('admin.contacts.show');
        Route::patch('contacts/{contact}/reply', [AdminContactController::class, 'markReplied'])->name('admin.contacts.reply');
        Route::delete('contacts/{contact}', [AdminContactController::class, 'destroy'])->name('admin.contacts.destroy');
    });
});
