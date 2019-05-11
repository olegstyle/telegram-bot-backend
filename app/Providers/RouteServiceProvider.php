<?php

namespace App\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

class RouteServiceProvider extends ServiceProvider
{
    protected $namespace = 'App\Http\Controllers';

    public function boot(): void
    {
        parent::boot();
    }

    public function map(): void
    {
        Route::prefix('api')
            ->middleware('api')
            ->namespace('App\Http\Controllers\Api')
            ->group(base_path('routes/api.php'));
    }
}
