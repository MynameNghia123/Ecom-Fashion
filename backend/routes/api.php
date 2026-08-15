<?php

use App\Http\Controllers\Admin\AttributeController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\BlogController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\GoodReceiptController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\ProductController;
use App\Http\Controllers\Admin\ProductVariantController;
use App\Http\Controllers\Admin\ReturnRequestController;
use App\Http\Controllers\Admin\ReviewController;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\StaffController;
use App\Http\Controllers\Admin\StatisticController;
use App\Http\Controllers\Admin\SupplierController;
use App\Http\Controllers\Admin\UploadController;
use App\Http\Controllers\Client\AiChatController as ClientAiChatController;
use App\Http\Controllers\Client\AuthController as ClientAuthController;
use App\Http\Controllers\Client\BannerController as ClientBannerController;
use App\Http\Controllers\Client\BlogController as ClientBlogController;
use App\Http\Controllers\Client\CartController as ClientCartController;
use App\Http\Controllers\Client\CustomerAddressController;
use App\Http\Controllers\Client\NotificationController as ClientNotificationController;
use App\Http\Controllers\Client\OrderController as ClientOrderController;
use App\Http\Controllers\Client\ProductController as ClientProductController;
use App\Http\Controllers\Client\SePayController as ClientSePayController;
use App\Http\Controllers\Client\ShippingController as ClientShippingController;
use App\Http\Controllers\Client\VNPayController as ClientVNPayController;
use App\Http\Controllers\Client\WishlistController;
use App\Models\Category;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
*/

// ── Public Admin Auth (không cần token) ─────────────────────────────────────
Route::post('admin/auth/login', [AuthController::class, 'login']);

// ── Protected Admin Routes ───────────────────────────────────────────────────
Route::prefix('admin')->middleware(['auth:sanctum'])->group(function () {

    Route::get('product-variants/search', [ProductVariantController::class, 'search']);
    Route::get('/suppliers/dropdown', [SupplierController::class, 'getSupplierForDropDown']);

    // ── Auth Info ────────────────────────────────────────────────────────────
    Route::post('auth/logout', [AuthController::class, 'logout']);
    Route::get('auth/me', [AuthController::class, 'me']);

    // ── Catalog ───────────────────────────────────────────────────────────────
    Route::apiResource('attributes', AttributeController::class)->middleware('permission:attributes');

    Route::get('categories/parents', [CategoryController::class, 'parents'])->middleware('permission:categories');
    Route::apiResource('categories', CategoryController::class)->middleware('permission:categories');

    Route::apiResource('products', ProductController::class)->middleware('permission:products');

    // ── Upload ────────────────────────────────────────────────────────────────
    Route::post('upload-image', [UploadController::class, 'upload']);
    Route::delete('upload-image', [UploadController::class, 'delete']);

    // ── Customers & Coupons ───────────────────────────────────────────────────
    Route::apiResource('customers', CustomerController::class)->middleware('permission:customers');
    Route::apiResource('coupons', CouponController::class)->middleware('permission:coupons');

    // ── Staff ─────────────────────────────────────────────────────────────────
    Route::apiResource('staffs', StaffController::class)->middleware('permission:staff');

    // ── RBAC: Roles ───────────────────────────────────────────────────────────
    Route::get('roles/all', [RoleController::class, 'all'])->middleware('permission:roles');
    Route::post('roles/{role}/sync-permissions', [RoleController::class, 'syncPermissions'])->middleware('permission:roles');
    Route::apiResource('roles', RoleController::class)->middleware('permission:roles');

    // ── RBAC: Permissions ─────────────────────────────────────────────────────
    Route::get('permissions/all', [PermissionController::class, 'getAll'])->middleware('permission:permissions');
    Route::apiResource('permissions', PermissionController::class)->only(['index'])->middleware('permission:permissions');

    // ── Supplier & Good Receipts ──────────────────────────────────────────────
    Route::apiResource('suppliers', SupplierController::class)->middleware('permission:suppliers');
    Route::apiResource('goods-receipts', GoodReceiptController::class)->middleware('permission:goods_receipts');

    // ── Content: Blog ─────────────────────────────────────────────────────────
    Route::apiResource('blogs', BlogController::class)->middleware('permission:blogs');

    // ── Marketing: Banners ───────────────────────────────────────────────────
    Route::apiResource('banners', BannerController::class)->middleware('permission:banners');

    // ── Orders ────────────────────────────────────────────────────────────────
    Route::apiResource('orders', OrderController::class)->only(['index', 'show', 'update', 'store'])->middleware('permission:orders');

    // ── Reviews ───────────────────────────────────────────────────────────────
    Route::apiResource('reviews', ReviewController::class)->only(['index', 'destroy'])->middleware('permission:reviews');

    // ── Return Requests ───────────────────────────────────────────────────────
    Route::get('return-requests', [ReturnRequestController::class, 'index']);
    Route::post('return-requests', [ReturnRequestController::class, 'store']);
    Route::get('return-requests/{returnRequest}', [ReturnRequestController::class, 'show']);
    Route::patch('return-requests/{returnRequest}/status', [ReturnRequestController::class, 'updateStatus']);

    // ── Statistics ────────────────────────────────────────────────────────────
    Route::prefix('statistics')->group(function () {
        Route::get('dashboard', [StatisticController::class, 'dashboard']);
        Route::get('top-products', [StatisticController::class, 'topProducts']);
        Route::get('low-stock', [StatisticController::class, 'lowStock']);
    });
});

// ── Public Client Routes (không yêu cầu xác thực) ───────────────────────────
Route::prefix('client')->group(function () {

    Route::get('blogs', [ClientBlogController::class, 'index']);
    Route::get('blogs/{slug}', [ClientBlogController::class, 'show']);
    Route::get('banners', [ClientBannerController::class, 'index']);

    // Products & Brands
    Route::get('products', [ClientProductController::class, 'index']);
    Route::get('products/brands', [ClientProductController::class, 'brands']);
    Route::get('products/top-rated', [ClientProductController::class, 'topRated']);
    Route::get('products/{idOrSlug}', [ClientProductController::class, 'show']);
    Route::get('products/{idOrSlug}/reviews', [App\Http\Controllers\Client\ReviewController::class, 'productReviews']);

    // GHN Shipping proxy
    Route::prefix('shipping')->group(function () {
        Route::get('provinces', [ClientShippingController::class, 'provinces']);
        Route::get('districts', [ClientShippingController::class, 'districts']);
        Route::get('wards', [ClientShippingController::class, 'wards']);
        Route::get('services', [ClientShippingController::class, 'services']);
        Route::post('fee', [ClientShippingController::class, 'fee']);
    });

    // Categories (public — for filter sidebar & mega menu)
    Route::get('categories/tree', function () {
        $roots = Category::whereNull('parent_id')
            ->with(['children.children'])
            ->orderBy('name')
            ->get();

        return response()->json(['success' => true, 'data' => $roots]);
    });
    Route::get('categories', [AdminCategoryController::class, 'index']);

    // AI Chat proxy
    Route::post('ai/chat', [ClientAiChatController::class, 'chat']);
    Route::get('brands/{domain}', function ($domain) {
        $apiKey = config('services.brandfetch.key');
        $response = Http::withToken($apiKey)->get("https://api.brandfetch.io/v2/brands/domain/{$domain}");
        if ($response->failed()) {
            return response()->json(['message' => 'Brand not found'], 404);
        }

        return $response->json();
    });

    // ── Public Client Auth ───────────────────────────────────────────────────
    Route::prefix('auth')->group(function () {
        Route::post('register', [ClientAuthController::class, 'register']);
        Route::post('login', [ClientAuthController::class, 'login']);
        Route::post('forgot-password', [ClientAuthController::class, 'forgotPassword']);
        Route::post('verify-otp', [ClientAuthController::class, 'verifyOtp']);
        Route::post('reset-password', [ClientAuthController::class, 'resetPassword']);
    });

    Route::prefix('auth')->middleware(['auth:sanctum'])->group(function () {
        Route::post('logout', [ClientAuthController::class, 'logout']);
        Route::get('me', [ClientAuthController::class, 'me']);
        Route::put('profile', [ClientAuthController::class, 'updateProfile']);
        Route::put('change-password', [ClientAuthController::class, 'changePassword']);
    });

    // ── VNPAY (public - VNPAY server gọi không có token) ────────────────────
    Route::get('vnpay/return', [ClientVNPayController::class, 'verifyReturn']);
    Route::post('vnpay/ipn', [ClientVNPayController::class, 'ipn']);

    // ── SePay (public webhook + public check) ─────────────────────────────
    Route::post('sepay/webhook', [ClientSePayController::class, 'webhook']);
    Route::get('sepay/check/{orderCode}', [ClientSePayController::class, 'checkStatus']);
    Route::get('sepay/info/{orderCode}', [ClientSePayController::class, 'paymentInfo']);

    // ── Protected Client Routes (yêu cầu đăng nhập customer) ────────────────
    Route::middleware(['auth:sanctum'])->group(function () {

        // Cart
        Route::get('cart', [ClientCartController::class, 'index']);
        Route::post('cart/sync', [ClientCartController::class, 'syncCart']);
        Route::post('cart/items', [ClientCartController::class, 'addItem']);
        Route::put('cart/items/{id}', [ClientCartController::class, 'updateItem']);
        Route::delete('cart/items/{id}', [ClientCartController::class, 'removeItem']);

        // Orders
        Route::get('orders', [ClientOrderController::class, 'index']);
        Route::post('orders', [ClientOrderController::class, 'store']);
        Route::get('orders/{code}', [ClientOrderController::class, 'show']);

        // Coupons
        Route::get('coupons', [App\Http\Controllers\Client\CouponController::class, 'index']);
        Route::get('coupons/collectable', [App\Http\Controllers\Client\CouponController::class, 'collectable']);
        Route::post('coupons/collect', [App\Http\Controllers\Client\CouponController::class, 'collect']);
        Route::post('coupons/apply', [App\Http\Controllers\Client\CouponController::class, 'apply']);

        // Addresses
        Route::get('addresses', [CustomerAddressController::class, 'index']);
        Route::post('addresses', [CustomerAddressController::class, 'store']);
        Route::put('addresses/{id}', [CustomerAddressController::class, 'update']);
        Route::delete('addresses/{id}', [CustomerAddressController::class, 'destroy']);
        // Reviews
        Route::get('reviews', [App\Http\Controllers\Client\ReviewController::class, 'index']);
        Route::post('reviews', [App\Http\Controllers\Client\ReviewController::class, 'store']);
        Route::get('products/{productId}/review-eligibility', [App\Http\Controllers\Client\ReviewController::class, 'checkEligibility']);

        // Wishlist
        Route::get('wishlist', [WishlistController::class, 'index']);
        Route::post('wishlist/toggle', [WishlistController::class, 'toggle']);
        Route::delete('wishlist/{productId}', [WishlistController::class, 'destroy']);

        // AI Chat History & Sync
        Route::get('ai/history', [ClientAiChatController::class, 'history']);
        Route::post('ai/sync-guest-history', [ClientAiChatController::class, 'syncGuestHistory']);

        // Return Requests
        Route::get('return-requests', [App\Http\Controllers\Client\ReturnRequestController::class, 'index']);
        Route::post('return-requests', [App\Http\Controllers\Client\ReturnRequestController::class, 'store']);
        Route::get('return-requests/{id}', [App\Http\Controllers\Client\ReturnRequestController::class, 'show']);

        // Notifications
        Route::get('notifications', [ClientNotificationController::class, 'index']);
        Route::get('notifications/unread-count', [ClientNotificationController::class, 'unreadCount']);
        Route::patch('notifications/read-all', [ClientNotificationController::class, 'markAllAsRead']);
        Route::patch('notifications/{id}/read', [ClientNotificationController::class, 'markAsRead']);
    });
});
