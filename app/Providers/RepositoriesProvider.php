<?php

namespace App\Providers;

use App\Repositories\Comment\CommentRepository;
use App\Repositories\Comment\CommentRepositoryContract;
use App\Repositories\Log\LogRepository;
use App\Repositories\Log\LogRepositoryContract;
use App\Repositories\News\NewsRepository;
use App\Repositories\News\NewsRepositoryContract;
use App\Repositories\Role\RoleRepository;
use App\Repositories\Role\RoleRepositoryContract;
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
        $this->app->bind(LogRepositoryContract::class, LogRepository::class);
        $this->app->bind(CommentRepositoryContract::class, CommentRepository::class);
        $this->app->bind(RoleRepositoryContract::class, RoleRepository::class);
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
