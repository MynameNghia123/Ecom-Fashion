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

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->group(function () {

    // ── Catalog ───────────────────────────────────────────────────────────────
    Route::apiResource('attributes', AttributeController::class);
    Route::get('categories/parents', [CategoryController::class, 'parents']);
    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('products', ProductController::class);

    // ── Upload ────────────────────────────────────────────────────────────────
    Route::post('upload-image',   [UploadController::class, 'upload']);
    Route::delete('upload-image', [UploadController::class, 'delete']);

    // ── Customers & Coupons ───────────────────────────────────────────────────
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('coupons',   CouponController::class);

    // ── Staff ─────────────────────────────────────────────────────────────────
    Route::apiResource('staffs', StaffController::class);

    // ── RBAC: Roles ───────────────────────────────────────────────────────────
    // GET  /admin/roles/all              — toàn bộ roles (dùng dropdown)
    // POST /admin/roles/{role}/sync-permissions — sync quyền riêng
    // Khai báo trước apiResource để tránh conflict với route {role}
    Route::get('roles/all', [RoleController::class, 'all']);
    Route::post('roles/{role}/sync-permissions', [RoleController::class, 'syncPermissions']);
    Route::apiResource('roles', RoleController::class);

    // ── RBAC: Permissions ─────────────────────────────────────────────────────
    // GET /admin/permissions/all — toàn bộ permissions (dùng form gán quyền)
    // Khai báo trước apiResource để tránh bị route {permission} capture
    Route::get('permissions/all', [PermissionController::class, 'getAll']);
    Route::apiResource('permissions', PermissionController::class)->only(['index']);
});