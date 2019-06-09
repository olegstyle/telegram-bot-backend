<?php declare(strict_types=1);

namespace App\Providers;

use App\Models\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        Schema::defaultStringLength(191);
    }

    public function boot(): void
    {
        $this->bindAuthRouteParam('bot');
        $this->bindAuthRouteParam('botChat');
        $this->bindAuthRouteParam('post');
        $this->bindAuthRouteParam('schedule');
    }

    private function bindAuthRouteParam(string $key): void
    {
        Route::bind($key, static function (string $id) use ($key) {
            /** @var User $user */
            $user = request()->user();

            return $user->{Str::plural($key)}()->whereId($id)->firstOrFail();
        });
    }
}
