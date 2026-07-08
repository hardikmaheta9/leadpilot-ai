<?php

namespace App\Providers;
use App\Models\Company;
use App\Observers\CompanyObserver;
use App\Repositories\ActivityRepository;
use App\Repositories\Contracts\ActivityRepositoryInterface;
use App\Repositories\CompanyRepository;
use App\Repositories\Contracts\CompanyRepositoryInterface;
use App\Services\CompanyService;
use App\Services\Contracts\CompanyServiceInterface;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(
            ActivityRepositoryInterface::class,
            ActivityRepository::class
        );

        $this->app->bind(
            CompanyRepositoryInterface::class,
            CompanyRepository::class
        );

        $this->app->bind(
            CompanyServiceInterface::class,
            CompanyService::class
        );
    }

       public function boot(): void
        {
            Company::observe(CompanyObserver::class);
        }
}