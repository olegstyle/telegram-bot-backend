<?php declare(strict_types=1);

namespace App\Console\Commands;

use App\DataTransferObjects\Message;
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
        $this->restartCheck();
        foreach (Schedule::all() as $schedule) {
            $this->scheduleRun($schedule);
        }
        $this->loop->run();
        /** @noinspection PhpUnhandledExceptionInspection */
        cache()->forget(RestartBots::getCacheKey(self::class));
    }

    protected function restartCheck(): void
    {
        $this->loop->addTimer(5, function () {
            if (!$this->restartReceived()) {
                $this->restartCheck();
            }
        });
    }

    protected function restartReceived(): bool
    {
        /** @noinspection PhpUnhandledExceptionInspection */
        if (cache()->has(RestartBots::getCacheKey(self::class))) {
            $this->loop->stop();
            return true;
        }

        return false;
    }

    protected function scheduleRun(Schedule $schedule): void
    {
        $intervalCall = function () use ($schedule) {
            $this->loop->addTimer(self::WAIT_SECONDS, function () use ($schedule) {
                $this->scheduleRun($schedule);
            });
        };
        $schedule->refresh();
        if (!$schedule->expression->isDue()) {
            $intervalCall();
            return;
        }

        /** @var Post $post */
        $post = $schedule->action ? $schedule->action->actionModel : null;
        if ($post === null) {
            $this->info('nothing to do with schedule #' . $schedule->id . '...');
            return;
        }

        foreach ($schedule->botChats as $chat) {
            $this->info('Sending post #' . $post->id . ' to chat #' . $chat->id . '(' . $chat->chat_id . ') by schedule #' . $schedule->id . '...');
            $message = new Message($post->message);
            $message->setPhotoPath($post->getPhotoFullPath());
            (new TelegramBot($this->loop, $chat->bot))->sendMessage($chat, $message);
        }

        $intervalCall();
    }
}
