<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\ReturnRequestController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\CustomerAddressController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\GoodReceiptController;
use App\Http\Controllers\Admin\ProductVariantController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ── Admin Auth (không cần middleware) ────────────────────────────────────────
Route::prefix('admin/auth')->group(function () {
    Route::post('login',   [AuthController::class, 'login']);
    Route::post('logout',  [AuthController::class, 'logout'])->middleware('auth:staff');
    Route::get('me',       [AuthController::class, 'me'])->middleware('auth:staff');
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:staff');
});

// ── Admin routes — yêu cầu JWT token hợp lệ ─────────────────────────────────
Route::prefix('admin')->middleware('auth:staff')->group(function () {
    Route::apiResource('attributes', AttributeController::class);
    Route::get('categories/parents', [CategoryController::class, 'parents']);

    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('products', ProductController::class);

    Route::get('products/variants/search-sku', [ProductController::class, 'searchVariantBySku']);

    // Product Variant search
    Route::get('product-variants/search', [ProductVariantController::class, 'search']);

    // Customer search
    Route::get('customers/search', [CustomerController::class, 'search']);
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('customer-addresses', CustomerAddressController::class);

    // Coupon check
    Route::post('coupons/check', [CouponController::class, 'check']);
    Route::apiResource('coupons', CouponController::class);
    Route::apiResource('blogs', BlogController::class);
    Route::apiResource('banners', BannerController::class);
    Route::apiResource('return-requests', ReturnRequestController::class);
    Route::apiResource('orders', OrderController::class);

    Route::apiResource('goods-receipts', GoodReceiptController::class);

    // field supplier drop down — phải đặt TRƯỚC apiResource để tránh bị route {supplier} override
    Route::get('/suppliers/dropdown', [SupplierController::class, 'getSupplierForDropDown']);
    Route::apiResource('suppliers', SupplierController::class);

    // Staff
    Route::apiResource('staffs', StaffController::class);

    // Roles & Permissions
    Route::get('roles/all', [RoleController::class, 'getAll']);
    Route::apiResource('roles', RoleController::class);
    Route::get('permissions', [PermissionController::class, 'index']);

    // Upload ảnh — trả về URL storage, không lưu ảnh vào DB
    Route::post('upload-image',   [UploadController::class, 'upload']);
    Route::delete('upload-image', [UploadController::class, 'delete']);
});