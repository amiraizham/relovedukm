<?php

use App\Http\Controllers\AuthManager;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ProductsManager;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\ForgotPasswordController;
use App\Http\Controllers\CartController;
use App\Http\Middleware\AdminMiddleware;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;




// Home route (Show welcome page if not logged in)
Route::get('/', function () {
    return Auth::check() ? redirect()->route('home') : view('welcome');
})->name('welcome');

// Authentication Routes
Route::get('/login', [AuthManager::class, 'login'])->name("login");
Route::post('/login', [AuthManager::class, 'loginPost'])->name("login.post");

Route::get('/register', [AuthManager::class, 'register'])->name("register");
Route::post('/register', [AuthManager::class, 'registerPost'])->name("register.post");

Route::get('/forgot-password', [ForgotPasswordController::class, 'showForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLink'])->name('password.email');


// Logout Route (Use POST for security)
Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('welcome');
})->name("logout");



// Protected Routes (Require User Authentication)
Route::middleware(['auth'])->group(function () {
    Route::get('/home', [ProductsManager::class, 'index'])->name("home");
    Route::get('/products', [ProductsManager::class, 'index'])->name('products');
    //Route::get('/products/{id}-{slug}', [ProductsManager::class, 'details'])->name('product.details');
    Route::get('/search', [ProductsManager::class, 'search'])->name('search');

    Route::get('/chats', [ChatController::class, 'chatList'])->name('chat.list');
    Route::get('/chat/{seller_id}', [ChatController::class, 'chatWithSeller'])->name('chat.seller');
    Route::post('/chat/send', [ChatController::class, 'sendMessage'])->name('chat.send');
    


    Route::post('/cart/add/{id}/{slug}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::delete('/cart/remove/{id}/{slug}', [CartController::class, 'remove'])->name('cart.remove');

    
    Route::get('/product/{id}/{slug}', [CartController::class, 'showProductDetails'])->name('product.details');
    Route::get('/products/{id}-{slug}/edit', [ProductsManager::class, 'edit'])->name('product.edit');
    Route::post('/products/{id}-{slug}/update', [ProductsManager::class, 'update'])->name('product.update');
    Route::delete('/products/{id}-{slug}/delete', [ProductsManager::class, 'delete'])->name('product.delete');
    Route::post('/product/{id}/mark-sold', [ProductsManager::class, 'markAsSold'])->name('product.markSold');


    //profile routes
    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::get('/products/{product}', [ProductsManager::class, 'show'])->name('products.show');
    Route::get('/seller/{matricnum}', [ProfileController::class, 'viewSellerProfile'])->name('seller.profile');





    // Selling Routes
    Route::get('/sell', [ProductsManager::class, 'showSellForm'])->name('sell'); // Show form
    Route::post('/sell', [ProductsManager::class, 'sellProduct'])->name('sell.post'); // Handle form submission
});

// Admin Routes
Route::prefix('admin')->group(function () {
    Route::get('/login', [AdminController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/login', [AdminController::class, 'login'])->name('admin.login.post');

    // Admin Logout (Use POST for security)
    Route::post('/logout', function () {
        Auth::logout();
        return redirect()->route('admin.login');
    })->name('admin.logout');

    // Admin Dashboard and Product Approvals (Requires Admin Authentication)
    Route::middleware(['auth', AdminMiddleware::class])->group(function () {
        Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
        Route::get('/products/pending', [AdminController::class, 'pendingProducts'])->name('admin.products.pending');
        Route::get('/admin/products/preview/{id}', [AdminController::class, 'preview'])->name('admin.products.preview');
        Route::post('/products/approve/{id}', [AdminController::class, 'approveProduct'])->name('admin.products.approve');
        Route::post('/products/reject/{id}', [AdminController::class, 'rejectProduct'])->name('admin.products.reject');
    });
});
