<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\ComplaintController;
use App\Http\Controllers\DiscountCodeController;



/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/register', [UserController::class, 'showRegisterForm'])->name('register');
// Route::post('/register', [UserController::class, 'register'])->name('register.submit');
// Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
// Route::post('/login', [UserController::class, 'login'])->name('login.submit');
// Route::get('/profile', [UserController::class, 'profile'])->middleware('auth')->name('profile');
// // Định nghĩa route home
// // Route::get('/home', function () {
// //     return view('home');
// // })->name('home');

// // Route cho chỉnh sửa thông tin cá nhân
// Route::get('/user/update', [UserController::class, 'edit'])->name('user.update')->middleware('auth');

// // Route cho xem lịch sử mua hàng
// Route::get('/user/orders', [OrderController::class, 'index'])->name('user.orders')->middleware('auth');

Route::middleware(['web'])->group(function () {
    Route::get('/register', [UserController::class, 'showRegisterForm'])->name('register');
    Route::post('/register', [UserController::class, 'register'])->name('register.submit');
    Route::get('/login', [UserController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [UserController::class, 'login'])->name('login.submit');
    Route::get('/profile', [UserController::class, 'profile'])->middleware('auth')->name('profile');
    Route::get('/user/edit', [UserController::class, 'edit'])->name('user.edit')->middleware('auth');
    Route::put('/user/update', [UserController::class, 'update'])->name('user.update')->middleware('auth');    Route::get('/user/orders', [OrderController::class, 'index'])->name('user.orders')->middleware('auth');
    Route::post('/logout', [UserController::class, 'logout'])->name('logout');
});


Route::get('/home', [App\Http\Controllers\ProductController::class, 'index'])->name('home');
// ->middleware('auth');

// Route::middleware(['auth', 'admin'])->group(function () {
//     Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');
//     Route::post('/products', [ProductController::class, 'store'])->name('products.store');
// });

// Hiển thị form thêm sản phẩm
Route::get('/products/create', [ProductController::class, 'create'])->name('products.create');

// Lưu sản phẩm mới
Route::post('/products', [ProductController::class, 'store'])->name('products.store');



Route::get('/categories/create', [CategoryController::class, 'create'])->name('categories.create');
Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
Route::get('/category/{category}', [ProductController::class, 'filterByCategory'])->name('products.category');
Route::get('/products/filter', [ProductController::class, 'filter'])->name('products.filter');
Route::post('/products/ajax-filter', [ProductController::class, 'ajaxFilter'])->name('products.ajaxFilter');
Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
Route::get('/products', [ProductController::class, 'index'])->name('products.index');

// Route để thêm sản phẩm vào giỏ hàng
Route::post('/cart/add', [CartController::class, 'addToCart'])->name('cart.add');


// Route để xem giỏ hàng

// Route để xóa sản phẩm khỏi giỏ hàng
Route::delete('/cart/remove/{id}', [CartController::class, 'remove'])->name('cart.remove');

Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.view');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');

// Route để thêm đánh giá và bình luận
// Route::post('/products/{id}/review', [ProductController::class, 'addReview'])->name('products.addReview')->middleware('auth');
Route::post('products/{id}/review', [ProductController::class, 'addOrUpdateReview'])->name('products.addReview');

// routes/web.php
Route::get('/checkout', [CheckoutController::class, 'checkout'])->name('checkout.index');
Route::post('/checkout', [CheckoutController::class, 'processCheckout'])->name('checkout.process');

Route::get('/orders/history', [OrderController::class, 'history'])->name('orders.history');

// Route dành cho khu vực quản trị
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/users', [UserController::class, 'index'])->name('admin.users.index');
    Route::post('/admin/users/{user}/change-role', [UserController::class, 'changeRole'])->name('admin.users.changeRole');
});

Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
Route::put('/products/{id}', [ProductController::class, 'update'])->name('products.update');
Route::post('/products/toggleStatus/{id}', [ProductController::class, 'toggleStatus'])->name('products.toggleStatus');
// Trang danh sách sản phẩm bị vô hiệu hóa
Route::get('/admin/disabled-products', [ProductController::class, 'disabledProducts'])->name('products.disabled');
Route::post('/reviews/{id}', [ProductReviewController::class, 'destroy'])->name('reviews.destroy');

// Route::post('/products/{id}/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
// Route::get('/admin/complaints', [ComplaintController::class, 'index'])->middleware('admin')->name('complaints.index');


// Route::middleware(['auth'])->group(function () {
//     Route::get('/complaints', [ComplaintController::class, 'index'])->name('complaints.index');
//     Route::get('/complaints/create', [ComplaintController::class, 'create'])->name('complaints.create');
//     Route::post('/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
//     Route::get('/complaints/{id}', [ComplaintController::class, 'show'])->name('complaints.show');
// });

// Route để hiển thị form khiếu nại (modal hoặc trang mới)
// Route::get('/products/{product}/complaint', [ComplaintController::class, 'create'])->name('complaints.create');



// // Route để gửi trả lời cho một khiếu nại
// Route::post('/complaints/{complaint}/reply', [ComplaintController::class, 'reply'])->name('admin.complaints.reply');

// // Route để lưu chi tiết khiếu nại
// Route::post('/orders/{order}/complaint', [ComplaintController::class, 'store'])->name('complaints.store');
// // Route để xem danh sách khiếu nại
// Route::get('/admin/complaints', [ComplaintController::class, 'index'])->name('admin.complaints.index')->middleware('admin');

// // Route để xem chi tiết một khiếu nại
// Route::get('/admin/complaints/{complaint}', [ComplaintController::class, 'show'])->name('admin.complaints.show')->middleware('admin');


// // Route để người dùng xem danh sách khiếu nại của họ
// Route::get('/my-complaints', [ComplaintController::class, 'userComplaints'])->name('complaints.user.index');

// // Route để người dùng xem chi tiết khiếu nại của họ
// Route::get('/complaints/{complaint}', [ComplaintController::class, 'userShow'])->name('complaints.user.show');

// // Route để người dùng trả lời phản hồi từ admin
// Route::post('/complaints/{complaint}/reply', [ComplaintController::class, 'userReply'])->name('complaints.user.reply');

// // Route cho người dùng và admin xem chi tiết khiếu nại
// Route::get('/complaints/{complaint}', [ComplaintController::class, 'show'])->name('complaints.show');

// // Route cho admin và người dùng gửi phản hồi cho khiếu nại
// Route::post('/complaints/{complaint}/reply', [ComplaintController::class, 'reply'])->name('complaints.reply');


Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/complaints', [ComplaintController::class, 'index'])->name('admin.complaints.index');
    Route::get('/admin/complaints/{complaint}', [ComplaintController::class, 'show'])->name('admin.complaints.show');
    Route::put('/admin/complaints/{complaint}/update-status', [ComplaintController::class, 'updateStatus'])->name('admin.complaints.updateStatus');
});

Route::middleware('auth')->group(function () {
    Route::post('/orders/{orderId}/complaints', [ComplaintController::class, 'store'])->name('complaints.store');
    Route::get('/complaints/{complaint}', [ComplaintController::class, 'show'])->name('complaints.show');
    Route::post('/complaints/{complaint}/reply', [ComplaintController::class, 'reply'])->name('complaints.reply');
});

// routes/web.php

Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/discount-codes', [DiscountCodeController::class, 'index'])->name('discount-codes.index');
    Route::get('/admin/discount-codes/create', [DiscountCodeController::class, 'create'])->name('discount-codes.create');
    Route::post('/admin/discount-codes', [DiscountCodeController::class, 'store'])->name('discount-codes.store');
    Route::get('/admin/discount-codes/{id}/edit', [DiscountCodeController::class, 'edit'])->name('discount-codes.edit');
    Route::put('/admin/discount-codes/{id}', [DiscountCodeController::class, 'update'])->name('discount-codes.update');
    Route::delete('/admin/discount-codes/{id}', [DiscountCodeController::class, 'destroy'])->name('discount-codes.destroy');
});
Route::post('/checkout/applyDiscount', [CheckoutController::class, 'applyDiscount'])->name('checkout.applyDiscount');


Route::middleware(['auth', 'admin'])->group(function () {
    Route::resource('discount-codes', DiscountCodeController::class)->except(['show']);
});

Route::get('/discounts/available', [CheckoutController::class, 'getAvailableDiscounts'])->name('discounts.available');
