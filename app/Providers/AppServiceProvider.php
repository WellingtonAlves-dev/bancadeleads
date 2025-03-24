<?php

namespace App\Providers;

use App\Hashing\Sha1Hasher;
use Illuminate\Hashing\HashManager;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {

        // Adiciona o SHA-1 como uma opção de hash
        $this->app->extend('hash', function (HashManager $hashManager) {
            $hashManager->extend('sha1', function () {
                return new Sha1Hasher();
            });

            return $hashManager;
        });


    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);
        Paginator::useBootstrap();
    }
}
