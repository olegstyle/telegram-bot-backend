<?php declare(strict_types=1);

namespace App\Services\Bots;

use App\DataTransferObjects\Message;
use App\Models\Bot;
use App\Models\BotChat;
use React\EventLoop\LoopInterface;
use React\Promise\PromiseInterface;

interface BotInterface
{
    public function __construct(LoopInterface $loop, Bot $bot);

    public function run(): PromiseInterface;
    public function sendMessage(BotChat $botChat, Message $message): PromiseInterface;
    public function getBot(): Bot;
}
