<?php

namespace App\Providers;

use App\Repositories\Admin\Implements\AttributeRepository;
use App\Repositories\Admin\Interfaces\AttributeRepositoryInterface;
use App\Services\Admin\Implements\AttributeService;
use App\Services\Admin\Interfaces\AttributeServiceInterface;

use App\Repositories\Admin\Implements\CategoryRepository;
use App\Repositories\Admin\Implements\ProductImageRepository;
use App\Repositories\Admin\Implements\ProductRepository;
use App\Repositories\Admin\Implements\ProductVariantRepository;
use App\Repositories\Admin\Implements\AttributeValueRepository;  // ← fix: Implements, không phải Interfaces
use App\Repositories\Admin\Implements\BlogRepository;
use App\Repositories\Admin\Interfaces\AttributeValueRepositoryInterface;
use App\Repositories\Admin\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Admin\Interfaces\ProductImageRepositoryInterface;
use App\Repositories\Admin\Interfaces\ProductRepositoryInterface;
use App\Repositories\Admin\Interfaces\ProductVariantRepositoryInterface;
use App\Repositories\Admin\Interfaces\CustomerRepositoryInterface;
use App\Repositories\Admin\Implements\CustomerRepository;
use App\Repositories\Admin\Interfaces\CouponRepositoryInterface;
use App\Repositories\Admin\Implements\CouponRepository;
use App\Repositories\Admin\Implements\SupplierRepository;
use App\Repositories\Admin\Interfaces\SupplierRepositoryInterface;

use App\Services\Admin\Implements\AttributeValueService;
use App\Services\Admin\Implements\CategoryService;
use App\Services\Admin\Implements\ProductImageService;
use App\Services\Admin\Implements\ProductService;
use App\Services\Admin\Implements\ProductVariantService;
use App\Services\Admin\Implements\CustomerService;
use App\Services\Admin\Implements\CouponService;
use App\Services\Admin\Implements\SupplierService;
use App\Services\Admin\Interfaces\AttributeValueServiceInterface;
use App\Services\Admin\Interfaces\CategoryServiceInterface;
use App\Services\Admin\Interfaces\ProductImageServiceInterface;
use App\Services\Admin\Interfaces\ProductServiceInterface;
use App\Services\Admin\Interfaces\ProductVariantServiceInterface;
use App\Services\Admin\Interfaces\CustomerServiceInterface;
use App\Services\Admin\Interfaces\CouponServiceInterface;
use App\Services\Admin\Interfaces\SupplierServiceInterface;

use App\Services\Admin\Interfaces\RoleServiceInterface;
use App\Services\Admin\Implements\RoleService;
use App\Services\Admin\Interfaces\PermissionServiceInterface;


use App\Services\Admin\Interfaces\GoodReceiptServiceInterface;
use App\Services\Admin\Implements\GoodReceiptService;

use App\Services\Admin\Interfaces\GoodReceiptDetailServiceInterface;
use App\Services\Admin\Implements\GoodReceiptDetailService;

use App\Repositories\Admin\Interfaces\GoodReceiptDetailRepoInterface;
use App\Repositories\Admin\Implements\GoodReceiptDetailRepo;

use App\Repositories\Admin\Interfaces\GoodReceiptRepoInterface;
use App\Repositories\Admin\Implements\GoodReceiptRepo;
use App\Repositories\Admin\Implements\PermissionRepo;
use App\Repositories\Admin\Implements\RoleRepo;
use App\Repositories\Admin\Implements\StaffRepo;
use App\Repositories\Admin\Interfaces\PermissionRepositoryInterface;
use App\Repositories\Admin\Interfaces\RoleRepositoryInterface;
use App\Repositories\Admin\Interfaces\StaffRepoInterface;
use App\Services\Admin\Implements\PermissionService;
use App\Services\Admin\Implements\StaffService;
use App\Services\Admin\Interfaces\StaffServiceInterface;
use App\Repositories\Admin\Implements\RolePermissionRepository;
use App\Repositories\Admin\Interfaces\RolePermissionRepositoryInterface;
use App\Services\Admin\Implements\RolePermissionService;
use App\Services\Admin\Interfaces\RolePermissionServiceInterface;

use App\Repositories\Admin\Interfaces\StaffRoleRepoInterface;
use App\Repositories\Admin\Implements\StaffRoleRepo;
use App\Services\Admin\Interfaces\StaffRoleServiceInterface;
use App\Services\Admin\Implements\StaffRoleService;

use App\Repositories\Admin\Interfaces\StaffPermissionRepoInterface;
use App\Repositories\Admin\Implements\StaffPermissionRepo;
use App\Repositories\Admin\Interfaces\BlogRepositoryInterface;
use App\Services\Admin\Interfaces\BlogServiceInterface;
use App\Services\Admin\Implements\BlogService;
use App\Repositories\Admin\Interfaces\BannerRepositoryInterface;
use App\Repositories\Admin\Implements\BannerRepository;
use App\Services\Admin\Interfaces\BannerServiceInterface;
use App\Services\Admin\Implements\BannerService;
use App\Services\Admin\Implements\StaffPermissionService;
use App\Services\Admin\Interfaces\StaffPermissionServiceInterface;

use App\Repositories\Admin\Interfaces\OrderRepositoryInterface;
use App\Repositories\Admin\Implements\OrderRepository;
use App\Services\Admin\Interfaces\OrderServiceInterface;
use App\Services\Admin\Implements\OrderService;

use App\Repositories\Admin\Interfaces\OrderDetailRepositoryInterface;
use App\Repositories\Admin\Implements\OrderDetailRepository;
use App\Services\Admin\Interfaces\OrderDetailServiceInterface;
use App\Services\Admin\Implements\OrderDetailService;

use App\Repositories\Admin\Interfaces\ReturnRequestRepositoryInterface;
use App\Repositories\Admin\Implements\ReturnRequestRepository;
use App\Services\Admin\Interfaces\ReturnRequestServiceInterface;
use App\Services\Admin\Implements\ReturnRequestService;

use Illuminate\Support\ServiceProvider;



class AppServiceProvider extends ServiceProvider
{
    /**
     * Bind Interface → Implementation.
     * Khi Laravel Container cần Interface, nó resolve thành class cụ thể.
     */
    public function register(): void
    {
        // ── Repositories ──────────────────────────────────────────────────
        // Attribute
        $this->app->bind(
            AttributeRepositoryInterface::class,
            AttributeRepository::class,
        );
        $this->app->bind(
            GoodReceiptDetailRepoInterface::class,
            GoodReceiptDetailRepo::class,
        );
        $this->app->bind(
            GoodReceiptDetailServiceInterface::class,
            GoodReceiptDetailService::class,
        );
        $this->app->bind(
            GoodReceiptRepoInterface::class,
            GoodReceiptRepo::class,
        );
        $this->app->bind(
            GoodReceiptServiceInterface::class,
            GoodReceiptService::class,
        );
        $this->app->bind(
            SupplierRepositoryInterface::class,
            SupplierRepository::class,
        );
        // Product
        $this->app->bind(
            ProductRepositoryInterface::class,
            ProductRepository::class,
        );
        // ProductImage
        $this->app->bind(
            ProductImageRepositoryInterface::class,
            ProductImageRepository::class,
        );
        // ProductVariant
        $this->app->bind(
            ProductVariantRepositoryInterface::class,
            ProductVariantRepository::class,
        );
        // AttributeValue
        $this->app->bind(
            AttributeValueRepositoryInterface::class,
            AttributeValueRepository::class,
        );
        // Category
        $this->app->bind(
            CategoryRepositoryInterface::class,
            CategoryRepository::class,
        );
        // Customer
        $this->app->bind(
            CustomerRepositoryInterface::class,
            CustomerRepository::class,
        );
        $this->app->bind(
            StaffRepoInterface::class,
            StaffRepo::class,
        );

        $this->app->bind(
            RoleRepositoryInterface::class,
            RoleRepo::class,
        );

        $this->app->bind(
            PermissionRepositoryInterface::class,
            PermissionRepo::class,  
        );

        $this->app->bind(
            RolePermissionRepositoryInterface::class,
            RolePermissionRepository::class,
        );

        $this->app->bind(
            StaffRoleRepoInterface::class,
            StaffRoleRepo::class,
        );

        $this->app->bind(
            StaffPermissionRepoInterface::class,
            StaffPermissionRepo::class,
        );
        // Coupon
        $this->app->bind(
            CouponRepositoryInterface::class,
            CouponRepository::class,
        );
        //Blog
        $this->app->bind(
            BlogRepositoryInterface::class,
            BlogRepository::class,
        );
        // Banner
        $this->app->bind(
            BannerRepositoryInterface::class,
            BannerRepository::class,
        );
        $this->app->bind(
            BannerServiceInterface::class,
            BannerService::class,
        );

        // Order, OrderDetail & ReturnRequest
        $this->app->bind(OrderRepositoryInterface::class, OrderRepository::class);
        $this->app->bind(OrderServiceInterface::class, OrderService::class);

        $this->app->bind(OrderDetailRepositoryInterface::class, OrderDetailRepository::class);
        $this->app->bind(OrderDetailServiceInterface::class, OrderDetailService::class);

        $this->app->bind(ReturnRequestRepositoryInterface::class, ReturnRequestRepository::class);
        $this->app->bind(ReturnRequestServiceInterface::class, ReturnRequestService::class);
        // Staff

        // ── Services ──────────────────────────────────────────────────────]
        // Blog
        $this->app->bind(
            BlogServiceInterface::class,
            BlogService::class,
        );
        
        // Attribute
        $this->app->bind(
            AttributeServiceInterface::class,
            AttributeService::class,
        );
        // Product
        $this->app->bind(
            ProductServiceInterface::class,
            ProductService::class,
        );
        $this->app->bind(
            SupplierServiceInterface::class,
            SupplierService::class,
        );
        // ProductImage
        $this->app->bind(
            ProductImageServiceInterface::class,
            ProductImageService::class,
        );
        // ProductVariant
        $this->app->bind(
            ProductVariantServiceInterface::class,
            ProductVariantService::class,
        );
        // AttributeValue
        $this->app->bind(
            AttributeValueServiceInterface::class,
            AttributeValueService::class,
        );
        // Category
        $this->app->bind(
            CategoryServiceInterface::class,
            CategoryService::class,
        );
        // Customer
        $this->app->bind(
            CustomerServiceInterface::class,
            CustomerService::class,
        );
        // Coupon
        $this->app->bind(
            CouponServiceInterface::class,
            CouponService::class,
        );
        // Staff 
        $this->app->bind(
            StaffServiceInterface::class,
            StaffService::class,
        );

        $this->app->bind(
            RoleServiceInterface::class,
            RoleService::class,
        );

        $this->app->bind(
            PermissionServiceInterface::class,
            PermissionService::class,
        );

        $this->app->bind(
            RolePermissionServiceInterface::class,
            RolePermissionService::class,
        );

        $this->app->bind(
            StaffRoleServiceInterface::class,
            StaffRoleService::class,
        );

        $this->app->bind(
            StaffPermissionServiceInterface::class,
            StaffPermissionService::class,
        );
    }

    public function boot(): void
    {
        //
    }
}
