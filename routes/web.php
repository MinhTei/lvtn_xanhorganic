<?php

use App\Http\Controllers\Clients\AccountController;
use App\Http\Controllers\Clients\AuthController;
use App\Http\Controllers\Clients\CartController;
use App\Http\Controllers\Clients\CategoryController;
use App\Http\Controllers\Clients\CheckoutController;
use App\Http\Controllers\Clients\ContactController;
use App\Http\Controllers\Clients\ForgotPasswordController;
use App\Http\Controllers\Clients\HomeController;
use App\Http\Controllers\Clients\ProductController;
use App\Http\Controllers\Clients\ProductDetailController;
use App\Http\Controllers\Clients\ResetPasswordController;
use App\Http\Controllers\Clients\WishlistController;
use Illuminate\Support\Facades\Route;



Route::get('/', fn () => redirect()->route('home'));
Route::get('/home', [HomeController::class, 'index'])->name('home');

Route::get('/contact', [ContactController::class, 'index'])->name('contact');
Route::post('/contact', [ContactController::class, 'store'])->name('contact.post');

Route::get('/about', function () {
    return view('clients.pages.about');
})->name('about');

Route::get('/products', [ProductController::class, 'index'])->name('products');
Route::get('/products/{slug}', [ProductDetailController::class, 'showProductDetail'])->name('product.detail');

Route::get('/categories', [CategoryController::class, 'showCategories'])->name('categories');
Route::get('/categories/{slug}', [CategoryController::class, 'showCategoriesBySlug'])->name('categories.show');

Route::get('/activate/{token}', [AuthController::class, 'activate'])->name('activate');

Route::middleware(['guest'])->group(function () {
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.post');

    Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [AuthController::class, 'register'])->name('register.post');

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showForgotPasswordForm'])->name('forgot.password');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('forgot.password.post');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'resetPassword'])->name('password.update');
});

Route::get('/wishlists', [WishlistController::class, 'index'])->name('wishlists');
Route::post('/wishlists/add', [WishlistController::class, 'store'])->name('wishlist.store');
Route::post('/wishlists/add-all-to-cart', [WishlistController::class, 'addAllToCart'])->name('wishlist.addAllToCart');
Route::delete('/wishlists/{wishlist?}', [WishlistController::class, 'destroy'])->name('wishlist.remove');


Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [CartController::class, 'store'])->name('cart.store');
Route::put('/cart/{product}', [CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/{product}', [CartController::class, 'destroy'])->name('cart.destroy');

Route::middleware('auth.custom')->group(function () {
    Route::match(['get', 'post'], '/logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('account')->group(function () {
        Route::get('/', [AccountController::class, 'index'])->name('account');
        Route::put('/update', [AccountController::class, 'update'])->name('account.update');
        Route::post('/change-password', [AccountController::class, 'changePassword'])->name('account.change.password');
        Route::post('/address', [AccountController::class, 'addAddress'])->name('account.address');
        Route::post('/address/{address}/default', [AccountController::class, 'setDefaultAddress'])->name('account.address.default');
        Route::delete('/address/{address}', [AccountController::class, 'destroyAddress'])->name('account.address.destroy');
        Route::put('/address/{address}', [AccountController::class, 'updateAddress'])->name('account.address.update');
        Route::get('/order/{order}', [AccountController::class, 'showOrder'])->name('account.order');
        Route::get('/order/{order}/detail', [AccountController::class, 'showOrderDetail'])->name('account.order.detail');
        Route::post('/order/{order}/cancel', [AccountController::class, 'cancelOrder'])->name('order-detail.cancel');
        Route::post('/order/{order}/review/{product}', [AccountController::class, 'storeReview'])->name('order-detail.review');
    });

    Route::put('/products/{product}/reviews/{review}', [ProductDetailController::class, 'updateReview'])->name('product.review.update');
    Route::delete('/products/{product}/reviews/{review}', [ProductDetailController::class, 'destroyReview'])->name('product.review.destroy');

    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.post');
    Route::post('/checkout/coupon', [CheckoutController::class, 'applyCoupon'])->name('checkout.coupon');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
});

Route::get('/vnpay/return', [\App\Http\Controllers\Clients\VnPayController::class, 'return'])->name('vnpay.return');
Route::get('/vnpay/ipn', [\App\Http\Controllers\Clients\VnPayController::class, 'ipn']);
Route::post('/vnpay/ipn', [\App\Http\Controllers\Clients\VnPayController::class, 'ipn'])->name('vnpay.ipn');

Route::get('/404', function () {
    return view('clients.pages.404');
});
