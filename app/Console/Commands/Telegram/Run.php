<?php declare(strict_types=1);

namespace App\Console\Commands\Telegram;

use App\Models\Bot;
use App\Models\BotChat;
use App\Services\Bots\Telegram\TelegramBot;
use Illuminate\Console\Command;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;

class Run extends Command
{
    public const INTERVAL_BETWEEN_RUNS = 5; // in seconds
    protected $signature = 'telegram:run';
    protected $description = 'Run handler for telegram bots.';

    /** @var LoopInterface */
    private $loop;

    public function handle(): void
    {
        $this->loop = Factory::create();
        foreach (Bot::all() as $bot) { // TODO pagination?
            $this->runBot($bot);
        }
        $this->loop->run();
    }

    protected function runBot(Bot $bot): void
    {
        $botService = new TelegramBot($this->loop, $bot);
        foreach ($bot->chats as $chat) {
            $this->runBotChat($botService, $chat);
        }
    }

    protected function runBotChat(TelegramBot $botService, BotChat $botChat): void
    {
        $intervalCall = function () use ($botService, $botChat) {
            $this->loop->addTimer(self::INTERVAL_BETWEEN_RUNS, function () use ($botService, $botChat) {
                $this->runBotChat($botService, $botChat);
            });
        };
        $botService->run($botChat)->then($intervalCall, $intervalCall);
    }
}
