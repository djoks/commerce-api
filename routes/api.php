<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\V1\AuthController;
use App\Http\Controllers\V1\RoleController;
use App\Http\Controllers\V1\UserController;
use App\Http\Controllers\V1\OrderController;
use App\Http\Controllers\V1\BillingController;
use App\Http\Controllers\V1\ProductController;
use App\Http\Controllers\V1\CategoryController;
use App\Http\Controllers\V1\CheckoutController;
use App\Http\Controllers\V1\DiscountController;
use App\Http\Controllers\V1\SupplierController;
use App\Http\Controllers\V1\PaymentTypeController;

/*
|--------------------------------------------------------------------------
| User Authentication Routes
|--------------------------------------------------------------------------
|
| Routes for user registration, verification, login, and logout.
| 
*/

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('verify', [AuthController::class, 'verify']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

/*
|--------------------------------------------------------------------------
| Publicly Accessible Routes
|--------------------------------------------------------------------------
|
| Routes that do not require authentication.
|
*/
Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
Route::apiResource('products', ProductController::class)->only(['index', 'show']);
Route::apiResource('payment-types', PaymentTypeController::class)->only(['index', 'show']);

/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
|
| Routes for checkout process and user account management that require user to be authenticated.
|
*/
Route::middleware(['auth:sanctum', 'throttle:api'])->group(function () {
    Route::post('checkout', [CheckoutController::class, 'checkout']);
    Route::post('checkout/{invoiceId}/otp', [CheckoutController::class, 'checkoutOtp']);

    Route::prefix('account')->group(function () {
        Route::apiResource('billing', BillingController::class);
        Route::get('orders', [OrderController::class, 'myOrders']);
    });
});

/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
|
| Routes for managing application data. These routes are intended for admin users.
|
*/
Route::middleware(['auth:sanctum', 'throttle:60,1'])->prefix('admin')->group(function () {
    Route::apiResource('roles', RoleController::class)->only(['index']);
    Route::apiResource('users', UserController::class);
    Route::patch('users/{id}/status', [UserController::class, 'updateStatus']);
    Route::patch('users/{id}/role', [UserController::class, 'updateRole']);
    Route::patch('users/update/password', [UserController::class, 'updatePassword']);

    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('payment-types', PaymentTypeController::class);
    Route::apiResource('suppliers', SupplierController::class);
    Route::apiResource('discounts', DiscountController::class);

    Route::apiResource('orders', OrderController::class);
});

/*
|--------------------------------------------------------------------------
| Utility Route
|--------------------------------------------------------------------------
|
| Route to retrieve authenticated user's information.
|
*/
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
