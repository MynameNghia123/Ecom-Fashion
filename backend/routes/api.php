<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\GoodReceiptController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Models\Supplier;

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BLogController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Client\BlogController as ClientBlogController;
use App\Http\Controllers\Client\BannerController as ClientBannerController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ── Protected Admin Routes ───────────────────────────────────────────────────
Route::prefix('admin')->middleware(['auth:sanctum'])->group(function () {
    
    Route::get('product-variants/search', [ProductVariantController::class, 'search']);
    Route::get('/suppliers/dropdown', [SupplierController::class, 'getSupplierForDropDown']);
    // ── Auth Info ────────────────────────────────────────────────────────────
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::get('auth/me',      [AuthController::class, 'me']);

    // ── Catalog ───────────────────────────────────────────────────────────────
    Route::apiResource('attributes', AttributeController::class)->middleware('permission:attributes');
    
    Route::get('categories/parents', [CategoryController::class, 'parents'])->middleware('permission:categories');
    Route::apiResource('categories', CategoryController::class)->middleware('permission:categories');
    
    Route::apiResource('products', ProductController::class)->middleware('permission:products');

    // ── Upload ────────────────────────────────────────────────────────────────
    // Upload image doesn't require a strict RBAC module check, but requires being an authenticated staff
    Route::post('upload-image',   [UploadController::class, 'upload']);
    Route::delete('upload-image', [UploadController::class, 'delete']);

    // ── Customers & Coupons ───────────────────────────────────────────────────
    Route::apiResource('customers', CustomerController::class)->middleware('permission:customers');
    Route::apiResource('coupons',   CouponController::class)->middleware('permission:coupons');

    // ── Staff ─────────────────────────────────────────────────────────────────
    Route::apiResource('staffs', StaffController::class)->middleware('permission:staff');

    // ── RBAC: Roles ───────────────────────────────────────────────────────────
    Route::get('roles/all', [RoleController::class, 'all'])->middleware('permission:roles');
    Route::post('roles/{role}/sync-permissions', [RoleController::class, 'syncPermissions'])->middleware('permission:roles');
    Route::apiResource('roles', RoleController::class)->middleware('permission:roles');

    // ── RBAC: Permissions ─────────────────────────────────────────────────────
    Route::get('permissions/all', [PermissionController::class, 'getAll'])->middleware('permission:permissions');
    Route::apiResource('permissions', PermissionController::class)->only(['index'])->middleware('permission:permissions');

    // ── Supplier & Good Receipts (Upstream Code) ────────────────────────────────
    Route::apiResource('suppliers', SupplierController::class)->middleware('permission:suppliers');
    Route::apiResource('goods-receipts', GoodReceiptController::class)->middleware('permission:goods_receipts');

    // ── Content: Blog ─────────────────────────────────────────────────────────
    Route::apiResource('blogs', BLogController::class)->middleware('permission:blogs');

    // ── Marketing: Banners ───────────────────────────────────────────────────
    Route::apiResource('banners', BannerController::class)->middleware('permission:banners');
});

// ── Public Client Routes (không yêu cầu xác thực) ───────────────────────────
Route::prefix('client')->group(function () {
    Route::get('blogs', [ClientBlogController::class, 'index']);
    Route::get('blogs/{slug}', [ClientBlogController::class, 'show']);
    Route::get('banners', [ClientBannerController::class, 'index']);
});