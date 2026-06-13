<?php

namespace App\Providers;

use App\Repositories\Admin\Implements\AttributeRepository;
use App\Repositories\Admin\Interfaces\AttributeRepositoryInterface;
use App\Services\Admin\Implements\AttributeService;
use App\Services\Admin\Interfaces\AttributeServiceInterface;

use App\Repositories\Admin\Implements\CategoryRepository;
use App\Repositories\Admin\Interfaces\CategoryRepositoryInterface;
use App\Services\Admin\Implements\CategoryService;
use App\Services\Admin\Interfaces\CategoryServiceInterface;

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
        // Category
        $this->app->bind(
            CategoryRepositoryInterface::class,
            CategoryRepository::class
        );

        // ── Services ──────────────────────────────────────────────────────
        // Attribute
        $this->app->bind(
            AttributeServiceInterface::class,
            AttributeService::class,
        );
        // Category
        $this->app->bind(
            CategoryServiceInterface::class,
            CategoryService::class
        );
    }

    public function boot(): void
    {
        //
    }
}
