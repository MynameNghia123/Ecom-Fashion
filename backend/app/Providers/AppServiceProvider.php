<?php

namespace App\Providers;

// ── Repositories ──────────────────────────────────────────────────────────────
use App\Repositories\Admin\Implements\AttributeRepository;
use App\Repositories\Admin\Implements\AttributeValueRepository;
use App\Repositories\Admin\Implements\BannerRepository;
use App\Repositories\Admin\Implements\BlogRepository;
use App\Repositories\Admin\Implements\CategoryRepository;
use App\Repositories\Admin\Implements\CouponRepository;
use App\Repositories\Admin\Implements\CustomerRepository;
use App\Repositories\Admin\Implements\PermissionRepository;
use App\Repositories\Admin\Implements\ProductImageRepository;
use App\Repositories\Admin\Implements\ProductRepository;
use App\Repositories\Admin\Implements\ProductVariantRepository;
use App\Repositories\Admin\Implements\RoleRepository;
use App\Repositories\Admin\Implements\StaffRepository;
use App\Repositories\Admin\Implements\SupplierRepository;
use App\Repositories\Admin\Implements\GoodReceiptRepo;
use App\Repositories\Admin\Implements\GoodReceiptDetailRepo;
use App\Repositories\Admin\Implements\OrderRepository;
use App\Repositories\Admin\Implements\ReturnRequestRepository;
use App\Repositories\Admin\Implements\ReviewRepository;
use App\Repositories\Admin\Implements\StatisticRepository;

use App\Repositories\Admin\Interfaces\AttributeRepositoryInterface;
use App\Repositories\Admin\Interfaces\AttributeValueRepositoryInterface;
use App\Repositories\Admin\Interfaces\BannerRepositoryInterface;
use App\Repositories\Admin\Interfaces\BlogRepositoryInterface;
use App\Repositories\Admin\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Admin\Interfaces\CouponRepositoryInterface;
use App\Repositories\Admin\Interfaces\CustomerRepositoryInterface;
use App\Repositories\Admin\Interfaces\PermissionRepositoryInterface;
use App\Repositories\Admin\Interfaces\ProductImageRepositoryInterface;
use App\Repositories\Admin\Interfaces\ProductRepositoryInterface;
use App\Repositories\Admin\Interfaces\ProductVariantRepositoryInterface;
use App\Repositories\Admin\Interfaces\RoleRepositoryInterface;
use App\Repositories\Admin\Interfaces\StaffRepositoryInterface;
use App\Repositories\Admin\Interfaces\SupplierRepositoryInterface;
use App\Repositories\Admin\Interfaces\GoodReceiptRepoInterface;
use App\Repositories\Admin\Interfaces\GoodReceiptDetailRepoInterface;
use App\Repositories\Admin\Interfaces\OrderRepositoryInterface;
use App\Repositories\Admin\Interfaces\ReturnRequestRepositoryInterface;
use App\Repositories\Admin\Interfaces\ReviewRepositoryInterface;
use App\Repositories\Admin\Interfaces\StatisticRepositoryInterface;

// ── Client Repositories ───────────────────────────────────────────────────────
use App\Repositories\Client\Implements\BannerRepository as ClientBannerRepository;
use App\Repositories\Client\Interfaces\BannerRepositoryInterface as ClientBannerRepositoryInterface;
use App\Repositories\Client\Implements\BlogRepository as ClientBlogRepository;
use App\Repositories\Client\Interfaces\BlogRepositoryInterface as ClientBlogRepositoryInterface;
use App\Repositories\Client\Implements\CouponRepository as ClientCouponRepository;
use App\Repositories\Client\Interfaces\CouponRepositoryInterface as ClientCouponRepositoryInterface;
use App\Repositories\Client\Implements\ProductRepository as ClientProductRepository;
use App\Repositories\Client\Interfaces\ProductRepositoryInterface as ClientProductRepositoryInterface;
use App\Repositories\Client\Implements\ReviewRepository as ClientReviewRepository;
use App\Repositories\Client\Interfaces\ReviewRepositoryInterface as ClientReviewRepositoryInterface;
use App\Repositories\Client\Implements\WishlistRepository;
use App\Repositories\Client\Interfaces\WishlistRepositoryInterface;
use App\Repositories\Client\Implements\CartRepository;
use App\Repositories\Client\Interfaces\CartRepositoryInterface;
use App\Repositories\Client\Implements\CustomerAddressRepository;
use App\Repositories\Client\Interfaces\CustomerAddressRepositoryInterface;
use App\Repositories\Client\Implements\OrderRepository as ClientOrderRepository;
use App\Repositories\Client\Interfaces\OrderRepositoryInterface as ClientOrderRepositoryInterface;
use App\Repositories\Client\Implements\AuthRepository as ClientAuthRepository;
use App\Repositories\Client\Interfaces\AuthRepositoryInterface as ClientAuthRepositoryInterface;
use App\Repositories\Client\Implements\AiChatRepository;
use App\Repositories\Client\Interfaces\AiChatRepositoryInterface;

// ── Services ──────────────────────────────────────────────────────────────────
use App\Services\Admin\Implements\AuthService;
use App\Services\Admin\Implements\AttributeService;
use App\Services\Admin\Implements\AttributeValueService;
use App\Services\Admin\Implements\BannerService;
use App\Services\Admin\Implements\BlogService;
use App\Services\Admin\Implements\CategoryService;
use App\Services\Admin\Implements\CouponService;
use App\Services\Admin\Implements\CustomerService;
use App\Services\Admin\Implements\PermissionService;
use App\Services\Admin\Implements\ProductImageService;
use App\Services\Admin\Implements\ProductService;
use App\Services\Admin\Implements\ProductVariantService;
use App\Services\Admin\Implements\RoleService;
use App\Services\Admin\Implements\StaffService;
use App\Services\Admin\Implements\SupplierService;
use App\Services\Admin\Implements\GoodReceiptService;
use App\Services\Admin\Implements\GoodReceiptDetailService;
use App\Services\Admin\Implements\OrderService;
use App\Services\Admin\Implements\ReturnRequestService;
use App\Services\Admin\Implements\ReviewService;
use App\Services\Admin\Implements\StatisticService;

use App\Services\Admin\Interfaces\AuthServiceInterface;
use App\Services\Admin\Interfaces\AttributeServiceInterface;
use App\Services\Admin\Interfaces\AttributeValueServiceInterface;
use App\Services\Admin\Interfaces\BannerServiceInterface;
use App\Services\Admin\Interfaces\BlogServiceInterface;
use App\Services\Admin\Interfaces\CategoryServiceInterface;
use App\Services\Admin\Interfaces\CouponServiceInterface;
use App\Services\Admin\Interfaces\CustomerServiceInterface;
use App\Services\Admin\Interfaces\PermissionServiceInterface;
use App\Services\Admin\Interfaces\ProductImageServiceInterface;
use App\Services\Admin\Interfaces\ProductServiceInterface;
use App\Services\Admin\Interfaces\ProductVariantServiceInterface;
use App\Services\Admin\Interfaces\RoleServiceInterface;
use App\Services\Admin\Interfaces\StaffServiceInterface;
use App\Services\Admin\Interfaces\SupplierServiceInterface;
use App\Services\Admin\Interfaces\GoodReceiptServiceInterface;
use App\Services\Admin\Interfaces\GoodReceiptDetailServiceInterface;
use App\Services\Admin\Interfaces\OrderServiceInterface;
use App\Services\Admin\Interfaces\ReturnRequestServiceInterface;
use App\Services\Admin\Interfaces\ReviewServiceInterface;
use App\Services\Admin\Interfaces\StatisticServiceInterface;

// ── Client Services ───────────────────────────────────────────────────────────
use App\Services\Client\Implements\BannerService as ClientBannerService;
use App\Services\Client\Interfaces\BannerServiceInterface as ClientBannerServiceInterface;
use App\Services\Client\Implements\BlogService as ClientBlogService;
use App\Services\Client\Interfaces\BlogServiceInterface as ClientBlogServiceInterface;
use App\Services\Client\Implements\CouponService as ClientCouponService;
use App\Services\Client\Interfaces\CouponServiceInterface as ClientCouponServiceInterface;
use App\Services\Client\Implements\ProductService as ClientProductService;
use App\Services\Client\Interfaces\ProductServiceInterface as ClientProductServiceInterface;
use App\Services\Client\Implements\ReviewService as ClientReviewService;
use App\Services\Client\Interfaces\ReviewServiceInterface as ClientReviewServiceInterface;
use App\Services\Client\Implements\WishlistService;
use App\Services\Client\Interfaces\WishlistServiceInterface;
use App\Services\Client\Implements\CartService;
use App\Services\Client\Interfaces\CartServiceInterface;
use App\Services\Client\Implements\CustomerAddressService;
use App\Services\Client\Interfaces\CustomerAddressServiceInterface;
use App\Services\Client\Implements\OrderService as ClientOrderService;
use App\Services\Client\Interfaces\OrderServiceInterface as ClientOrderServiceInterface;
use App\Services\Client\Implements\AuthService as ClientAuthService;
use App\Services\Client\Interfaces\AuthServiceInterface as ClientAuthServiceInterface;
use App\Services\Client\Implements\PaymentService;
use App\Services\Client\Interfaces\PaymentServiceInterface;
use App\Services\Client\Implements\AiChatService;
use App\Services\Client\Interfaces\AiChatServiceInterface;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bind Interface → Implementation.
     */
    public function register(): void
    {
        // ── Repositories ──────────────────────────────────────────────────────
        $this->app->bind(AttributeRepositoryInterface::class,       AttributeRepository::class);
        $this->app->bind(AttributeValueRepositoryInterface::class,  AttributeValueRepository::class);
        $this->app->bind(BannerRepositoryInterface::class,          BannerRepository::class);
        $this->app->bind(BlogRepositoryInterface::class,            BlogRepository::class);
        $this->app->bind(CategoryRepositoryInterface::class,        CategoryRepository::class);
        $this->app->bind(CouponRepositoryInterface::class,          CouponRepository::class);
        $this->app->bind(CustomerRepositoryInterface::class,        CustomerRepository::class);
        $this->app->bind(PermissionRepositoryInterface::class,      PermissionRepository::class);
        $this->app->bind(ProductRepositoryInterface::class,         ProductRepository::class);
        $this->app->bind(ProductImageRepositoryInterface::class,    ProductImageRepository::class);
        $this->app->bind(ProductVariantRepositoryInterface::class,  ProductVariantRepository::class);
        $this->app->bind(RoleRepositoryInterface::class,            RoleRepository::class);
        $this->app->bind(StaffRepositoryInterface::class,           StaffRepository::class);
        $this->app->bind(SupplierRepositoryInterface::class,        SupplierRepository::class);
        $this->app->bind(GoodReceiptRepoInterface::class,           GoodReceiptRepo::class);
        $this->app->bind(GoodReceiptDetailRepoInterface::class,     GoodReceiptDetailRepo::class);
        $this->app->bind(OrderRepositoryInterface::class,           OrderRepository::class);
        $this->app->bind(ReturnRequestRepositoryInterface::class,   ReturnRequestRepository::class);
        $this->app->bind(ReviewRepositoryInterface::class,          ReviewRepository::class);
        $this->app->bind(StatisticRepositoryInterface::class,       StatisticRepository::class);

        // ── Services ──────────────────────────────────────────────────────────
        $this->app->bind(AuthServiceInterface::class,               AuthService::class);
        $this->app->bind(AttributeServiceInterface::class,          AttributeService::class);
        $this->app->bind(AttributeValueServiceInterface::class,     AttributeValueService::class);
        $this->app->bind(BannerServiceInterface::class,             BannerService::class);
        $this->app->bind(BlogServiceInterface::class,               BlogService::class);
        $this->app->bind(CategoryServiceInterface::class,           CategoryService::class);
        $this->app->bind(CouponServiceInterface::class,             CouponService::class);
        $this->app->bind(CustomerServiceInterface::class,           CustomerService::class);
        $this->app->bind(PermissionServiceInterface::class,         PermissionService::class);
        $this->app->bind(ProductServiceInterface::class,            ProductService::class);
        $this->app->bind(ProductImageServiceInterface::class,       ProductImageService::class);
        $this->app->bind(ProductVariantServiceInterface::class,     ProductVariantService::class);
        $this->app->bind(RoleServiceInterface::class,               RoleService::class);
        $this->app->bind(StaffServiceInterface::class,              StaffService::class);
        $this->app->bind(SupplierServiceInterface::class,           SupplierService::class);
        $this->app->bind(GoodReceiptServiceInterface::class,        GoodReceiptService::class);
        $this->app->bind(GoodReceiptDetailServiceInterface::class,  GoodReceiptDetailService::class);
        $this->app->bind(OrderServiceInterface::class,              OrderService::class);
        $this->app->bind(ReturnRequestServiceInterface::class,      ReturnRequestService::class);
        $this->app->bind(ReviewServiceInterface::class,             ReviewService::class);
        $this->app->bind(StatisticServiceInterface::class,          StatisticService::class);

        // Client bindings
        $this->app->bind(ClientBannerRepositoryInterface::class, ClientBannerRepository::class);
        $this->app->bind(ClientBannerServiceInterface::class, ClientBannerService::class);
        $this->app->bind(ClientBlogRepositoryInterface::class, ClientBlogRepository::class);
        $this->app->bind(ClientBlogServiceInterface::class, ClientBlogService::class);
        $this->app->bind(ClientCouponRepositoryInterface::class, ClientCouponRepository::class);
        $this->app->bind(ClientCouponServiceInterface::class, ClientCouponService::class);
        $this->app->bind(ClientProductRepositoryInterface::class, ClientProductRepository::class);
        $this->app->bind(ClientProductServiceInterface::class, ClientProductService::class);
        $this->app->bind(ClientReviewRepositoryInterface::class, ClientReviewRepository::class);
        $this->app->bind(ClientReviewServiceInterface::class, ClientReviewService::class);
        $this->app->bind(WishlistRepositoryInterface::class, WishlistRepository::class);
        $this->app->bind(WishlistServiceInterface::class, WishlistService::class);
        $this->app->bind(CartRepositoryInterface::class, CartRepository::class);
        $this->app->bind(CartServiceInterface::class, CartService::class);
        $this->app->bind(CustomerAddressRepositoryInterface::class, CustomerAddressRepository::class);
        $this->app->bind(CustomerAddressServiceInterface::class, CustomerAddressService::class);
        $this->app->bind(ClientOrderRepositoryInterface::class, ClientOrderRepository::class);
        $this->app->bind(ClientOrderServiceInterface::class, ClientOrderService::class);
        $this->app->bind(ClientAuthRepositoryInterface::class, ClientAuthRepository::class);
        $this->app->bind(ClientAuthServiceInterface::class, ClientAuthService::class);
        $this->app->bind(PaymentServiceInterface::class, PaymentService::class);
        $this->app->bind(AiChatRepositoryInterface::class, AiChatRepository::class);
        $this->app->bind(AiChatServiceInterface::class, AiChatService::class);
    }

    public function boot(): void
    {
        \Illuminate\Support\Facades\Gate::before(function ($user, $ability) {
            // $ability expected format: "module.action"
            $parts = explode('.', $ability);
            if (count($parts) === 2) {
                [$module, $action] = $parts;

                // Super admin check is already inside hasPermission,
                // but we can rely entirely on the model's method
                if (method_exists($user, 'hasPermission') && $user->hasPermission($module, $action)) {
                    return true;
                }
            }
            return null; // Let other gates run if this doesn't match
        });
    }
}
