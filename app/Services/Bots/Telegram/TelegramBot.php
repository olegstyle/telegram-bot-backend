<?php declare(strict_types=1);

namespace App\Services\Bots\Telegram;

use App\Models\Bot;
use App\Models\BotChat;
use App\Services\Bots\BotInterface;
use Carbon\Carbon;
use Closure;
use Exception;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use React\EventLoop\LoopInterface;
use React\Promise\Promise;
use React\Promise\PromiseInterface;
use unreal4u\TelegramAPI\Abstracts\TraversableCustomType;
use unreal4u\TelegramAPI\HttpClientRequestHandler;
use unreal4u\TelegramAPI\Telegram\Methods\GetUpdates;
use unreal4u\TelegramAPI\Telegram\Methods\SendMessage;
use unreal4u\TelegramAPI\TgLog;

class TelegramBot implements BotInterface
{
    protected const MAX_MINUTES_FOR_HANDLE_UPDATE = 10;

    protected $loop;
    protected $bot;
    protected $logger;

    public function __construct(LoopInterface $loop, Bot $bot)
    {
        $this->loop = $loop;
        $this->bot = $bot;
        $this->logger = app(Logger::class);
    }

    public function run(BotChat $botChat): PromiseInterface
    {
        $tgLog = $this->getTelegram($this->loop);

        $getUpdates = new GetUpdates();
        $getUpdates->offset = $this->getOffsetForChat($botChat);

        return $tgLog->performApiRequest($getUpdates)->then(
            function (TraversableCustomType $updatesArray) use ($botChat) {
                return $this->handleUpdates($botChat, $updatesArray);
            },
            function (Exception $exception) {
                Log::error('Exception ' . get_class($exception) . ' caught, message: ' . $exception->getMessage());
            }
        );
    }

    protected function getTelegram(LoopInterface $loop): TgLog
    {
        return new TgLog($this->bot->token, new HttpClientRequestHandler($loop), $this->logger);
    }

    protected function getOffsetForChat(BotChat $botChat): int
    {
        return (int) Cache::get($this->getOffsetCacheKey($botChat), 0);
    }

    protected function getOffsetCacheKey(BotChat $botChat): string
    {
        return 'telegram-offset:' . $this->bot->id . ':' . $botChat->id;
    }

    protected function handleUpdates(BotChat $botChat, TraversableCustomType $updatesArray): Promise
    {
        return new Promise(function (Closure $resolve/*, \Closure $reject*/) use ($botChat, $updatesArray) {
            if ($updatesArray === null) {
                return;
            }
            $latestOffset = $this->getOffsetForChat($botChat);

            foreach ($updatesArray as $update) {
                // skip offset id
                if ($update->update_id === $latestOffset) {
                    continue;
                }
                // skip bots and not new chat member listener ???
                if ($update->message->new_chat_member === null || $update->message->new_chat_member->is_bot) {
                    continue;
                }
                // skip old messages
                if (Carbon::createFromTimestamp($update->message->date)->lessThan(Carbon::now()->subMinutes(static::MAX_MINUTES_FOR_HANDLE_UPDATE))) {
                    continue;
                }

                // TODO MOVE TO ANOTHER CLASS
                // increment welcome message counter
                $this->incrementWelcomeMessageCounter($botChat);
                if (!$this->shouldSendWelcomeMessage($botChat)) {
                    continue;
                }

                $this->sendWelcomeMessage( // TODO MOVE PART OF CODE TO ANOTHER CLASS + EVENT
                    $botChat,
                    $update->message->new_chat_member->first_name,
                    $update->message->new_chat_member->last_name,
                    $update->message->new_chat_member->id
                );
            }

            $this->setOffsetForChat($botChat, (int) end($updatesArray->data)->update_id);
            $resolve();
        });
    }

    protected function sendWelcomeMessage(BotChat $botChat, string $name, string $lasName, $id): PromiseInterface // Move to another class
    {
        $username = $name . ' ' . $lasName;

        $welcomeList = [ // TODO - move data to BD
            'Hello [' . $username . '](tg://user?id=' . $id . '). Welcome to the community!',
            'Welcome [' . $username . '](tg://user?id=' . $id . ') to community. If you have any questions feel free to ask. We are here to help.',
            'Hi [' . $username . '](tg://user?id=' . $id . ')! Welcome to our community! Check the pinned message in the top for general info. If you have any questions, feel free to ask!',
            'Thank you for joining [' . $username . '](tg://user?id=' . $id . ')! Glad to have you here in our community. ',
            'Hello [' . $username . '](tg://user?id=' . $id . ')! Welcome to Telegram group! We encourage you to read our pinned message containing links on our updates and further information or check out our website as well. If you have any feedback or questions, we will gladly answer them.',
            'Welcome! We have a pinned message above to give you some basic details about our project, feel free to look around!',
            'Thank you for joining our group! We are all happy to have you here. We offer 24/7 assistance for new and existing members so feel free to ask questions!',
            'Hi, we\'re glad to have you here! Feel free to ask us any questions here!',
        ];

        return $this->sendMessage($botChat, $welcomeList[random_int(0, count($welcomeList) - 1)]);
    }

    protected function incrementWelcomeMessageCounter(BotChat $botChat): void
    {
        $counter = $this->getWelcomeMessageCounter($botChat);
        $counter++;
        if ($counter >= 1) { // TODO from database
            $counter = 0;
        }
        Cache::forever($this->getWelcomeMessageCounterCacheKey($botChat), $counter);
    }

    // TODO START REFACTOR TO ANOTHER EVENT CLASS

    protected function getWelcomeMessageCounter(BotChat $botChat): int
    {
        return (int) Cache::get($this->getWelcomeMessageCounterCacheKey($botChat), 0);
    }

    protected function getWelcomeMessageCounterCacheKey(BotChat $botChat): string
    {
        return 'telegram-welcome-counter:' . $this->bot->id . ':' . $botChat->id;
    }

    public function shouldSendWelcomeMessage(BotChat $botChat): bool
    {
        return $this->getWelcomeMessageCounter($botChat) === 0;
    }

    protected function setOffsetForChat(BotChat $botChat, int $offset): void
    {
        Cache::forever($this->getOffsetCacheKey($botChat), $offset);
    }

    public function sendMessage(BotChat $botChat, string $message, string $parseMode = 'Markdown'): PromiseInterface
    {
        $tgLog = $this->getTelegram($this->loop);
        $sendMessage = new SendMessage();
        $sendMessage->chat_id = '@' . $botChat->chat_id;
        $sendMessage->text = $message;
        $sendMessage->parse_mode = $parseMode;

        return $tgLog->performApiRequest($sendMessage);
    }

    // TODO END REFACTOR TO ANOTHER EVENT CLASS
}
