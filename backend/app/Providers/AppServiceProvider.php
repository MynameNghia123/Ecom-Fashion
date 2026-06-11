<?php

namespace App\Providers;

use App\Repositories\Admin\Implements\AttributeRepository;
use App\Repositories\Admin\Interfaces\AttributeRepositoryInterface;
use App\Services\Admin\Implements\AttributeService;
use App\Services\Admin\Interfaces\AttributeServiceInterface;
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
        $this->app->bind(
            AttributeRepositoryInterface::class,
            AttributeRepository::class
        );

        // ── Services ──────────────────────────────────────────────────────
        $this->app->bind(
            AttributeServiceInterface::class,
            AttributeService::class
        );
    }

    public function boot(): void
    {
        //
    }
}
