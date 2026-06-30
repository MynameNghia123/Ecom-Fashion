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
use App\Models\Supplier;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// Admin routes (thêm middleware auth:sanctum khi hoàn thiện auth)
Route::prefix('admin')->group(function () {
    Route::apiResource('attributes', AttributeController::class);
    Route::get('categories/parents', [CategoryController::class, 'parents']);

    Route::apiResource('categories', CategoryController::class);
    Route::apiResource('products', ProductController::class);
    Route::apiResource('customers', CustomerController::class);
    Route::apiResource('coupons', CouponController::class);
    Route::apiResource('staffs', StaffController::class);
    Route::apiResource('roles', RoleController::class);
    Route::apiResource('permissions', PermissionController::class);
    Route::apiResource('suppliers', SupplierController::class);
    Route::apiResource('good-receipts', GoodReceiptController::class);

    // Upload ảnh — trả về URL storage, không lưu ảnh vào DB
    Route::post('upload-image',    [UploadController::class, 'upload']);
    Route::delete('upload-image',  [UploadController::class, 'delete']);
});

//Route::get('/attributes', [AttributeController::class, 'index']);
//Route::post('/attributes', [AttributeController::class, 'store']);
//Route::get('/attributes/{attribute}', [AttributeController::class, 'show']);
//Route::put('/attributes/{attribute}', [AttributeController::class, 'update']);
//oute::delete('/attributes/{attribute}', [AttributeController::class, 'destroy']