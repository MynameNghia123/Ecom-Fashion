<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\ProductController;
<<<<<<< HEAD
use App\Http\Controllers\Admin\UploadController;
=======
use App\Http\Controllers\Admin\CustomerController;
>>>>>>> 830ad7097c39c8ec29a5256910ba5358561aaeda

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
<<<<<<< HEAD

    // Upload ảnh — trả về URL storage, không lưu ảnh vào DB
    Route::post('upload-image',    [UploadController::class, 'upload']);
    Route::delete('upload-image',  [UploadController::class, 'delete']);
=======
    Route::apiResource('customers', CustomerController::class);
>>>>>>> 830ad7097c39c8ec29a5256910ba5358561aaeda
});

//Route::get('/attributes', [AttributeController::class, 'index']);
//Route::post('/attributes', [AttributeController::class, 'store']);
//Route::get('/attributes/{attribute}', [AttributeController::class, 'show']);
//Route::put('/attributes/{attribute}', [AttributeController::class, 'update']);
//oute::delete('/attributes/{attribute}', [AttributeController::class, 'destroy']