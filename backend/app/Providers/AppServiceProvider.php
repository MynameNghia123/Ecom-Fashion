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

// ── Services ──────────────────────────────────────────────────────────────────
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

        // ── Services ──────────────────────────────────────────────────────────
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
