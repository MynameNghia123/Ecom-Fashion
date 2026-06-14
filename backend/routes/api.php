<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\CategoryController;

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
});
//Route::get('/attributes', [AttributeController::class, 'index']);
//Route::post('/attributes', [AttributeController::class, 'store']);
//Route::get('/attributes/{attribute}', [AttributeController::class, 'show']);
//Route::put('/attributes/{attribute}', [AttributeController::class, 'update']);
//oute::delete('/attributes/{attribute}', [AttributeController::class, 'destroy']