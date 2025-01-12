<?php

namespace App\Providers;

use App\Repositories\Contracts\ActivityRepositoryInterface;
use App\Repositories\Contracts\BuildingRepositoryInterface;
use App\Repositories\Contracts\OrganizationRepositoryInterface;
use App\Repositories\Eloquent\ActivityRepository;
use App\Repositories\Eloquent\BuildingRepository;
use App\Repositories\Eloquent\OrganizationRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(OrganizationRepositoryInterface::class, OrganizationRepository::class);
        $this->app->bind(BuildingRepositoryInterface::class, BuildingRepository::class);
        $this->app->bind(ActivityRepositoryInterface::class, ActivityRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
