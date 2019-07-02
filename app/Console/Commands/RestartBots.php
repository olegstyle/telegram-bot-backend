<?php declare(strict_types=1);

namespace App\Console\Commands;

use Carbon\Carbon;
use Illuminate\Console\Command;

class RestartBots extends Command
{
    public const RESTART_CACHE_PREFIX = 'restart-bots-commands:';
    public const CACHE_LIVE_MINUTES = 5;

    protected $signature = 'bots:restart';
    protected $description = 'restart each bots.';

    protected $commandsToRestart = [
        BotScheduler::class,
        Telegram\Run::class,
    ];

    public function handle(): void
    {
        foreach ($this->commandsToRestart as $command) {
            /** @noinspection PhpUnhandledExceptionInspection */
            cache()->set(self::getCacheKey($command), '1', Carbon::now()->addMinutes(self::CACHE_LIVE_MINUTES));
        }
    }

    public static function getCacheKey(string $command): string
    {
        return self::RESTART_CACHE_PREFIX . sha1($command);
    }
}
