<?php

namespace App\Providers;

use App\Repositories\News\NewsRepository;
use App\Repositories\News\NewsRepositoryContract;
use App\Repositories\Storage\StorageRepository;
use App\Repositories\Storage\StorageRepositoryContract;
use App\Repositories\User\UserRepository;
use App\Repositories\User\UserRepositoryContract;
use Illuminate\Support\ServiceProvider;

class RepositoriesProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserRepositoryContract::class, UserRepository::class);
        $this->app->bind(NewsRepositoryContract::class, NewsRepository::class);
        $this->app->bind(StorageRepositoryContract::class, StorageRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
