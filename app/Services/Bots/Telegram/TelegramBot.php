<?php declare(strict_types=1);

namespace App\Services\Bots\Telegram;

use App\Enums\BotChatSettingName;
use App\Enums\ChatEvent;
use App\Enums\Telegram\ChatType;
use App\Models\Bot;
use App\Models\BotChat;
use App\Models\BotChatSetting;
use App\Models\BotEvent;
use App\Services\Bots\BotInterface;
use Carbon\Carbon;
use Closure;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Log\Logger;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use React\EventLoop\LoopInterface;
use React\Promise\FulfilledPromise;
use React\Promise\Promise;
use React\Promise\PromiseInterface;
use unreal4u\TelegramAPI\Abstracts\TraversableCustomType;
use unreal4u\TelegramAPI\HttpClientRequestHandler;
use unreal4u\TelegramAPI\Telegram\Methods\GetUpdates;
use unreal4u\TelegramAPI\Telegram\Methods\SendMessage;
use unreal4u\TelegramAPI\Telegram\Methods\SendPhoto;
use unreal4u\TelegramAPI\Telegram\Types\Custom\InputFile;
use unreal4u\TelegramAPI\Telegram\Types\Update;
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

    public function run(): PromiseInterface
    {
        $this->bot->refresh();
        $tgLog = $this->getTelegram();

        $getUpdates = new GetUpdates();
        $getUpdates->offset = $this->getOffset();

        return $tgLog->performApiRequest($getUpdates)->then(
            function (TraversableCustomType $updatesArray) {
                return $this->handleUpdates($updatesArray);
            },
            function (Exception $exception) {
                Log::error('Exception ' . get_class($exception) . ' caught, message: ' . $exception->getMessage());
            }
        );
    }

    protected function getTelegram(): TgLog
    {
        return new TgLog($this->bot->token, new HttpClientRequestHandler($this->loop), $this->logger);
    }

    protected function getOffset(): int
    {
        return (int) Cache::get($this->getOffsetCacheKey(), 0);
    }

    protected function getOffsetCacheKey(): string
    {
        return 'telegram-offset:' . $this->bot->id;
    }

    protected function handleUpdates(TraversableCustomType $updatesArray): Promise
    {
        return new Promise(function (Closure $resolve/*, \Closure $reject*/) use ($updatesArray) {
            if ($updatesArray === null) {
                return;
            }
            foreach ($updatesArray as $update) {
                $this->handleUpdate($update);
            }

            $this->setOffset((int) end($updatesArray->data)->update_id);
            $resolve();
        });
    }

    protected function handleUpdate(Update $update): void
    {
        // skip offset id
        if ($update->update_id === $this->getOffset()) {
            return;
        }
        $message = $update->message;
        // skip old messages
        if (Carbon::createFromTimestamp($update->message->date)->lessThan(Carbon::now()->subMinutes(static::MAX_MINUTES_FOR_HANDLE_UPDATE))) {
            return;
        }

        if (!ChatType::isValid($message->chat->type) || !(new ChatType($message->chat->type))->isSupported()) {
            return; // unknown chat type or not supported
        }

        $chat = $this->getChatById($message->chat->username);
        if ($chat === null) {
            return; // unknown chat
        }

        // skip bots and not new chat member listener ???
        if ($message->new_chat_member === null || $message->new_chat_member->is_bot) {
            return;
        }

        // TODO add to analytic table.

        $this->sendWelcomeMessage( // TODO MOVE PART OF CODE TO ANOTHER CLASS + EVENT
            $chat,
            $message->new_chat_member->first_name . ' ' . $message->new_chat_member->last_name,
            (string) $update->message->new_chat_member->id
        );
    }

    protected function getChatById(string $chatId): ?BotChat
    {
        return $this->bot->chats->where('chat_id', $chatId)->first();
    }

    protected function sendWelcomeMessage(BotChat $botChat, string $username, string $id): PromiseInterface // Move to another class
    {
        // increment welcome message counter
        $this->incrementWelcomeMessageCounter($botChat);
        if (!$this->shouldSendWelcomeMessage($botChat)) {
            return new FulfilledPromise();
        }
        /** @var BotEvent[]|Collection $messageList */
        $messageList = $botChat->events()->whereEvent(ChatEvent::NEW_MEMBER())->whereActive()->get();
        if ($messageList->isEmpty()) {
            return new FulfilledPromise();
        }

        $random = 0;
        if ($messageList->count() > 1) {
            try {
                $random = random_int(0, $messageList->count() - 1);
            } catch (Exception $e) {
                Log::error('random_int not working :(');
            }
        }

        /** @var BotEvent $message */
        $message = $messageList->get($random);
        $message = $message->message;
        /** @var string $message */
        $message = str_replace(
            '%USERNAME%',
            "[{$username}](tg://user?id={$id})",
            $message
        ); // TODO create message builder with variables

        return $this->sendMessage($botChat, $message);
    }

    protected function incrementWelcomeMessageCounter(BotChat $botChat): void
    {
        $counter = $this->getWelcomeMessageCounter($botChat);
        $counter++;
        /** @var BotChatSetting $setting */
        $setting = $botChat->settings()->whereSetting(BotChatSettingName::WELCOME_MSG_DELAY())->first();
        $setting = $setting ? (int) $setting->value : 1;
        if ($counter >= $setting) {
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

    public function sendMessage(BotChat $botChat, string $message, string $parseMode = 'Markdown'): PromiseInterface
    {
        $tgLog = $this->getTelegram();
        $sendMessage = new SendMessage();
        $sendMessage->chat_id = '@' . $botChat->chat_id;
        $sendMessage->text = $message;
        $sendMessage->parse_mode = $parseMode;

        return $tgLog->performApiRequest($sendMessage);
    }

    protected function setOffset(int $offset): void
    {
        Cache::forever($this->getOffsetCacheKey(), $offset);
    }

    public function sendPhoto(BotChat $botChat, string $message, string $photoPath, string $parseMode = 'Markdown'): PromiseInterface
    {
        $tgLog = $this->getTelegram();
        $sendMessage = new SendPhoto();
        $sendMessage->chat_id = '@' . $botChat->chat_id;
        $sendMessage->photo = new InputFile($photoPath);
        $sendMessage->caption = $message;
        $sendMessage->parse_mode = $parseMode;

        return $tgLog->performApiRequest($sendMessage);
    }

    // TODO END REFACTOR TO ANOTHER EVENT CLASS
}
