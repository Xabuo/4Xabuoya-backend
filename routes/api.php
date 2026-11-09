
<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ShoppingCartController;
use App\Http\Controllers\CartItemController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\RatingAndReviewController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\CustomerNotificationController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\AdminNotificationController;
use App\Http\Controllers\GuestController;
use App\Http\Controllers\AuthController;
use App\Http\Middleware\EnsureAdmin;

// CUSTOMER AUTHENTICATION
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

// CUSTOMER PROFILE AND AUTHENTICATED ACTIONS
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum');

Route::get('/me', [AuthController::class, 'me'])
    ->middleware('auth:sanctum');

// ADMIN LOGIN - PUBLIC
Route::post('/admin/login', [AdminAuthController::class, 'login']);

// ADMIN-ONLY ROUTES
Route::middleware(['auth:sanctum', EnsureAdmin::class])->group(function () {
    
// ADMIN CUSTOMER MANAGEMENT
Route::get('/admin/customers', [
    CustomerController::class,
    'adminIndex'
]);

    Route::post('/admin/logout', [AdminAuthController::class, 'logout']);

    // ADMIN ORDER MANAGEMENT
Route::get('/admin/orders', [OrderController::class, 'adminIndex']);

Route::patch('/admin/orders/{order}/status', [
    OrderController::class,
    'adminUpdateStatus'
]);

// ADMIN SALES REPORTS
Route::get('/admin/sales-reports', [
    ReportController::class,
    'salesSummary'
]);

    Route::apiResource('report', ReportController::class);

    Route::apiResource('admin', AdminController::class)->except(['store']);

    Route::apiResource('admin-notification', AdminNotificationController::class);

    // ADMIN ORDER MANAGEMENT
Route::get('/admin/orders', [OrderController::class, 'adminIndex']);

Route::patch('/admin/orders/{order}/status', [
    OrderController::class,
    'adminUpdateStatus'
]);

// ADMIN CATEGORY MANAGEMENT
Route::post('/category', [CategoryController::class, 'store']);
Route::put('/category/{category}', [CategoryController::class, 'update']);
Route::patch('/category/{category}', [CategoryController::class, 'update']);
Route::delete('/category/{category}', [CategoryController::class, 'destroy']);

    // ADMIN PRODUCT MANAGEMENT
    Route::post('/products', [ProductController::class, 'store']);
    Route::put('/products/{product}', [ProductController::class, 'update']);
    Route::patch('/products/{product}', [ProductController::class, 'update']);
    Route::delete('/products/{product}', [ProductController::class, 'destroy']);
});

// ORDER MANAGEMENT - AUTHENTICATED
Route::apiResource('order', OrderController::class)
    ->middleware('auth:sanctum');

Route::post('/order/{order}/cancel', [OrderController::class, 'cancel'])
    ->middleware('auth:sanctum');

// PUBLIC CATEGORY VIEWING
Route::get('/category', [CategoryController::class, 'index']);
Route::get('/category/{category}', [CategoryController::class, 'show']);

// CUSTOMERS AND GUESTS CAN VIEW PRODUCTS
Route::get('/products', [ProductController::class, 'index']);
Route::get('/products/{product}', [ProductController::class, 'show']);

// OTHER RESOURCE ROUTES
Route::apiResource('customer', CustomerController::class);
Route::apiResource('shopping-cart', ShoppingCartController::class);
Route::apiResource('cart-item', CartItemController::class);
Route::apiResource('order-item', OrderItemController::class);

// PUBLIC: VIEW PRODUCT REVIEWS
Route::get('/rating-and-review', [RatingAndReviewController::class, 'index']);
Route::get('/rating-and-review/{ratingAndReview}', [RatingAndReviewController::class, 'show']);

// AUTHENTICATED: SUBMIT, EDIT, AND DELETE REVIEWS
Route::post('/rating-and-review', [RatingAndReviewController::class, 'store'])
    ->middleware('auth:sanctum');

Route::put('/rating-and-review/{ratingAndReview}', [RatingAndReviewController::class, 'update'])
    ->middleware('auth:sanctum');

Route::patch('/rating-and-review/{ratingAndReview}', [RatingAndReviewController::class, 'update'])
    ->middleware('auth:sanctum');

Route::delete('/rating-and-review/{ratingAndReview}', [RatingAndReviewController::class, 'destroy'])
    ->middleware('auth:sanctum');

// OTHER CUSTOMER RESOURCES
Route::apiResource('wishlist', WishlistController::class);
Route::apiResource('customer-notification', CustomerNotificationController::class);

// GUEST ROUTES
Route::get('/guest/products', [GuestController::class, 'products']);
