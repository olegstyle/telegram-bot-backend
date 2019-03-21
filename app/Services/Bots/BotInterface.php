<?php declare(strict_types=1);

namespace App\Services\Bots;

use App\Models\Bot;
use React\EventLoop\LoopInterface;
use React\Promise\PromiseInterface;

interface BotInterface
{
    public function __construct(LoopInterface $loop, Bot $bot);

    public function run(): PromiseInterface;
}
