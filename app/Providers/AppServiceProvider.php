<?php declare(strict_types=1);

namespace App\Providers;

use App\Models\BotChat;
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
        $this->bindAuthRouteParam('post');
        $this->bindAuthRouteParam('schedule');

        Route::bind('botChat', static function (string $id) {
            /** @var User $user */
            $user = request()->user();

            return $user->botChats()
                ->where(BotChat::getTableName().'.'.BotChat::getModelKeyName(), $id)
                ->firstOrFail();
        });
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
