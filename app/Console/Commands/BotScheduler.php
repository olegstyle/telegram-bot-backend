<?php declare(strict_types=1);

namespace App\Console\Commands;

use App\Models\Post;
use App\Models\Schedules\Schedule;
use App\Services\Bots\Telegram\TelegramBot;
use Illuminate\Console\Command;
use React\EventLoop\Factory;
use React\EventLoop\LoopInterface;

class BotScheduler extends Command
{
    public const WAIT_SECONDS = 60;

    protected $signature = 'bot-scheduler:run';
    protected $description = 'Run scheduler for each users/bots.';

    /** @var LoopInterface */
    private $loop;

    public function handle(): void
    {
        $this->loop = Factory::create();
        foreach (Schedule::all() as $schedule) {
            $this->scheduleRun($schedule);
        }
        $this->loop->run();
    }

    protected function scheduleRun(Schedule $schedule): void
    {
        $intervalCall = function () use ($schedule) {
            $this->loop->addTimer(self::WAIT_SECONDS, function () use ($schedule) {
                $this->scheduleRun($schedule);
            });
        };
        if (!$schedule->expression->isDue()) {
            $intervalCall();
        }

        /** @var Post $post */
        $post = $schedule->action->actionModel;
        if ($post === null) {
            return; // nothing to do with this schedule...
        }
        foreach ($schedule->botChats as $chat) {
            (new TelegramBot($this->loop, $chat->bot))->sendMessage($chat, $post->message)->then($intervalCall, $intervalCall);
        }
    }
}
