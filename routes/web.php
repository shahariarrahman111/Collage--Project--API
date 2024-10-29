<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WishListController;

Route::get('/home', function () {
    return view('welcome');
})->middleware('auth');





Route::post('/user-registration', [UserController::class,'UserRegister']);
Route::post('/user-login', [UserController::class,'UserLogin']);
Route::post('/otp/create', [ForgetPasswordController::class,'SendOTPCode']);
Route::post('/otp/verify', [ForgetPasswordController::class,'VerifyOTPCode']);
Route::post('/reset-password', [ForgetPasswordController::class, 'ResetPasswrod']);
Route::get('/products-list', [ProductController::class, 'ProductList']);
Route::get('/product/{id}', [ProductController::class, 'ShowProduct']);


Route::middleware('auth:sanctum')->group(function () {
    Route::post('/user-logout', [UserController::class,'UserLogout']);
    Route::post('/profile/create', [ProfileController::class,'CreateProfile']);
    Route::post('/profile', [ProfileController::class,'ShowProfile']);
    Route::post('/profile/update', [ProfileController::class,'UpdateProfile']);
    Route::post('/cart-add', [CartController::class, 'addToCart'])->name('cart.add'); 
    Route::get('/cart-show', [CartController::class, 'viewCart'])->name('cart.view');
    Route::post('/orders', [OrderController::class, 'storeOrder'])->name('order.store');
    Route::post('/add/wishlist', [WishListController::class, 'addToWishlist']);
    Route::get('/show/wishlist', [WishListController::class, 'getUserWishlist']);
    Route::delete('/delete/wishlist/{id}', [WishListController::class, 'removeFromWishlist']);

});


// Admin API

Route::middleware(['auth:api', 'role:admin'])->group(function () {
    Route::get('/dashboard', [AdminController::class,'index']);
    Route::get('/admin/dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/admin/profile', [AdminController::class, 'ShowAdminProfile']);
    Route::post('/admin/profile/update', [AdminController::class, 'UpdateAdminProfile']);
    
    // Category Admin

    Route::get('/categories', [CategoryController::class, 'CategoryList']);
    Route::post('/categories/create', [CategoryController::class, 'CreateCategory']);
    Route::get('/categories/{id}', [CategoryController::class, 'ShowCategory']);
    Route::put('/categories/update/{id}', [CategoryController::class,'UpdateCategory']);
    Route::delete('/categories/delete/{id}', [CategoryController::class, 'DeleteCategory']);



    // Admin products

    // Route::get('/dashboard', [DashboardController::class, 'index'])->name('admin.dashboard');
    Route::get('/products-list', [ProductController::class, 'ProductList']);
    Route::post('/product-create', [ProductController::class, 'CreateProduct']);
    Route::get('/product/{id}', [ProductController::class, 'ShowProduct']);
    // Route::get('/product/{id}/edit', [ProductController::class, 'edit']);
    Route::put('/product-update/{id}', [ProductController::class, 'Updateproduct']);
    Route::delete('/product-delete/{id}', [ProductController::class, 'DeleteProduct']);

    // Admin orders
    Route::get('/admin/orders/{userId}', [OrderController::class, 'showUserOrders']);


    //  User Profile Show
    Route::get('/admin/users', [ProfileController::class, 'UserProfileList'])->name('admin.users.index');
    Route::get('/admin/users/profile/{id}', [ProfileController::class, 'adminShowUserProfile'])->name('admin.users.profile');



  
});
