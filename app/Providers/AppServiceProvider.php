<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Route::macro('statusResource', function ($uri, $controller) {
            Route::get("{$uri}/statuses", [$controller, 'getStatuses'])->name("{$uri}.getStatuses");
            Route::patch("{$uri}/{id}/status", [$controller, 'changeStatus'])->name("{$uri}.changeStatus");
            Route::apiResource($uri, $controller);
        });

        Model::preventLazyLoading();
    }
}
