<?php

namespace App\Providers;

use App\Services\News\NewsService;
use App\Services\News\NewsServiceContract;
use App\Services\User\UserService;
use App\Services\User\UserServiceContract;
use Illuminate\Support\ServiceProvider;

class ServicesProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(UserServiceContract::class, UserService::class);
        $this->app->bind(NewsServiceContract::class, NewsService::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
