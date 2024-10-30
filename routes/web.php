<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ForgetPasswordController;
use App\Http\Controllers\InvoiceController;
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
    Route::post('/invoice/create', [InvoiceController::class, 'InvoiceCreate'])->name('invoice.create'); 
    Route::get('/invoices', [InvoiceController::class, 'InvoiceList'])->name('invoice.list'); 
    Route::get('/invoice/products/{invoice_id}', [InvoiceController::class, 'InvoiceProductList'])->name('invoice.product.list'); 

});


//  SSLCommerce er Route
Route::get('/payment/success', [InvoiceController::class, 'PaymentSuccess'])->name('payment.success');
Route::get('/payment/cancel', [InvoiceController::class, 'PaymentCancel'])->name('payment.cancel');
Route::get('/payment/fail', [InvoiceController::class, 'PaymentFail'])->name('payment.fail');
Route::post('/payment/ipn', [InvoiceController::class, 'PaymentIPN'])->name('payment.ipn');





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

    // Admin Invoice
    Route::get('/admin/invoices', [InvoiceController::class, 'InvoiceList'])->name('admin.invoice.list'); 
    Route::get('/admin/invoice/products/{invoice_id}', [InvoiceController::class, 'InvoiceProductList'])->name('admin.invoice.product.list'); 


  
});
