<?php

namespace App\Providers;

use App\Http\Middleware\GateDefineMiddleware;
use Illuminate\Support\ServiceProvider;
use Illuminate\Http\Resources\Json\JsonResource;

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
        JsonResource::withoutWrapping();
        $this->app->bind('check-permission-of-user-middleware', GateDefineMiddleware::class);
    }
}
