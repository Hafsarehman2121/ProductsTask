<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Http\Repositories\AuthRepositories\LoginRepo;
use App\Http\Repositories\AuthRepositories\LogoutRepo;
use App\Http\Repositories\AuthRepositories\RegisterRepo;
use App\Http\Repositories\ProductRepositories\CategoryRepo;
use App\Http\Repositories\ProductRepositories\ProductRepo;
use App\Http\Repositories\ProductRepositories\SubCategoryRepo;
use App\Http\Repositories\AuthRepositories\AuthRepositoryInterfaces\LoginRepoInterface;
use App\Http\Repositories\AuthRepositories\AuthRepositoryInterfaces\LogoutRepoInterface;
use App\Http\Repositories\ProductRepositories\ProductRepositoryInterfaces\ProductRepoInterface;
use App\Http\Repositories\ProductRepositories\ProductRepositoryInterfaces\CategoryRepoInterface;
use App\Http\Repositories\ProductRepositories\ProductRepositoryInterfaces\SubCategoryRepoInterface;
use App\Http\Repositories\AuthRepositories\AuthRepositoryInterfaces\RegisterRepoInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(RegisterRepoInterface::class, RegisterRepo::class);
        $this->app->bind(LoginRepoInterface::class, LoginRepo::class);
             $this->app->bind(LogoutRepoInterface::class, LogoutRepo::class);
        $this->app->bind(CategoryRepoInterface::class, CategoryRepo::class);
        $this->app->bind(SubCategoryRepoInterface::class, SubCategoryRepo::class);
         $this->app->bind(ProductRepoInterface::class, ProductRepo::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}
