<?php declare(strict_types=1);

namespace App\Jobs;

use App\DataTransferObjects\Message;
use App\Models\Post;
use App\Models\Schedules\Schedule;
use App\Services\Bots\Telegram\TelegramBot;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;
use React\EventLoop\Factory;

class RunSchedule implements ShouldQueue
{
    use InteractsWithQueue, Queueable;

    protected const CACHE_PREFIX = 'jobs:run-schedule';
    protected const CACHE_MINUTES = 1440; // 24 hours
    protected const QUEUE_NAME = 'scheduler';

    public $schedule;
    public $uuid;

    public function __construct(Schedule $schedule)
    {
        $this->queue = self::QUEUE_NAME;
        $this->schedule = $schedule;
        /** @noinspection PhpUnhandledExceptionInspection */
        $this->uuid = Uuid::uuid1()->toString();
        $this->startJob();
    }

    public function handle(): void
    {
        if ($this->isSkipped()) {
            return;
        }

        $this->schedule->load(['action', 'botChats']);

        /** @var Post $post */
        $post = $this->schedule->action ? $this->schedule->action->actionModel : null;
        if (!$post) {
            Log::info('run_schedule', [
                'flag' => 'nothing_to_do',
                'scheduleId' => $this->schedule->id,
            ]);
            return;
        }

        $loop = Factory::create();
        foreach ($this->schedule->botChats as $chat) {
            Log::info('Sending post #' . $post->id . ' to chat #' . $chat->id . '(' . $chat->chat_id . ') by schedule #' . $this->schedule->id . '...');
            $message = new Message($post->message);
            $message->setPhotoPath($post->getPhotoFullPath());
            (new TelegramBot($loop, $chat->bot))->sendMessage($chat, $message);
            Log::info('run_schedule', [
                'flag' => 'send',
                'scheduleId' => $this->schedule->id,
                'chatId' => $chat->chat_id,
                'postId' => $post->id,
            ]);
        }
        $this->stopJob();
        $loop->run();
    }

    public function isSkipped(): bool
    {
        // when key not exists in cache - than job already processed and should be skipped
        if (!Cache::has($this->getCacheKey())) {
            return true;
        }

        // when cached value not equals to job uuid then another job started for this address and current job should be skipped
        return Cache::get($this->getCacheKey()) !== $this->uuid;
    }

    protected function startJob(): void
    {
        Cache::put($this->getCacheKey(), $this->uuid, self::CACHE_MINUTES);
    }

    protected function stopJob(): void
    {
        Cache::forget($this->getCacheKey());
    }

    protected function getCacheKey(): string
    {
        return self::CACHE_PREFIX . $this->schedule->id;
    }
}
