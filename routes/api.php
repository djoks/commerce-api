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
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('auth')->group(function () {
    Route::post('register', [AuthController::class, 'register']);
    Route::post('verify', [AuthController::class, 'verify']);
    Route::post('login', [AuthController::class, 'login']);
    Route::post('logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
});

Route::apiResource('categories', CategoryController::class)->only(['index', 'show']);
Route::apiResource('products', ProductController::class)->only(['index', 'show']);
Route::apiResource('payment-types', PaymentTypeController::class)->only(['index', 'show']);

Route::middleware('auth:sanctum')->group(function () {


    Route::post('checkout', [CheckoutController::class, 'checkout']);
    Route::post('checkout/{invoiceId}/otp', [CheckoutController::class, 'checkoutOtp']);

    Route::prefix('account')->group(function () {
        Route::apiResource('billing', BillingController::class);
        Route::get('orders', [OrderController::class, 'myOrders']);
    });

    Route::prefix('admin')->group(function () {
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
});
