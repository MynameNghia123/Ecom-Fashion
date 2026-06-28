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
use App\Repositories\Admin\Interfaces\AttributeValueRepositoryInterface;
use App\Repositories\Admin\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Admin\Interfaces\ProductImageRepositoryInterface;
use App\Repositories\Admin\Interfaces\ProductRepositoryInterface;
use App\Repositories\Admin\Interfaces\ProductVariantRepositoryInterface;
use App\Repositories\Admin\Interfaces\CustomerRepositoryInterface;
use App\Repositories\Admin\Implements\CustomerRepository;
use App\Repositories\Admin\Interfaces\CouponRepositoryInterface;
use App\Repositories\Admin\Implements\StaffRepository;
use App\Repositories\Admin\Interfaces\StaffRepositoryInterface;
use App\Repositories\Admin\Implements\RoleRepository;
use App\Repositories\Admin\Interfaces\RoleRepositoryInterface;
use App\Repositories\Admin\Implements\PermissionRepository;
use App\Repositories\Admin\Implements\SupplierRepository;
use App\Repositories\Admin\Interfaces\SupplierRepositoryInterface;
use App\Repositories\Admin\Interfaces\PermissionRepositoryInterface;

use App\Services\Admin\Implements\AttributeValueService;
use App\Services\Admin\Implements\CategoryService;
use App\Services\Admin\Implements\ProductImageService;
use App\Services\Admin\Implements\ProductService;
use App\Services\Admin\Implements\ProductVariantService;
use App\Services\Admin\Implements\CustomerService;
use App\Services\Admin\Implements\CouponService;
use App\Services\Admin\Implements\StaffService;
use App\Services\Admin\Implements\RoleService;
use App\Services\Admin\Implements\PermissionService;
use App\Services\Admin\Implements\SupplierService;
use App\Services\Admin\Interfaces\AttributeValueServiceInterface;
use App\Services\Admin\Interfaces\CategoryServiceInterface;
use App\Services\Admin\Interfaces\ProductImageServiceInterface;
use App\Services\Admin\Interfaces\ProductServiceInterface;
use App\Services\Admin\Interfaces\ProductVariantServiceInterface;
use App\Services\Admin\Interfaces\CustomerServiceInterface;
use App\Services\Admin\Interfaces\CouponServiceInterface;
use App\Services\Admin\Interfaces\StaffServiceInterface;
use App\Services\Admin\Interfaces\RoleServiceInterface;
use App\Services\Admin\Interfaces\PermissionServiceInterface;
use App\Services\Admin\Interfaces\SupplierServiceInterface;
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
        // Coupon
        // $this->app->bind(
        //     CouponRepositoryInterface::class,
        //     CouponRepository::class,
        // );
        // Staff
        $this->app->bind(
            StaffRepositoryInterface::class,
            StaffRepository::class,
        );

        // ── Services ──────────────────────────────────────────────────────
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
        // $this->app->bind(
        //     CouponRepositoryInterface::class,
        //     CouponRepository::class,
        // );
        // Staff 
        $this->app->bind(
            StaffServiceInterface::class,
            StaffService::class,
        );
        // Role
        $this->app->bind(
            RoleServiceInterface::class,
            RoleService::class,
        ); 
       
    }

    public function boot(): void
    {
        //
    }
}
