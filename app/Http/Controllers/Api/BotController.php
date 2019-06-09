<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\JsonRequest;
use App\Http\Requests\Bot\CreateOrUpdateBotChatRequest;
use App\Http\Requests\Bot\CreateBotRequest;
use App\Http\Resources\BotChatResource;
use App\Http\Resources\BotResource;
use App\Http\Resources\ResourceCollection;
use App\Http\Responses\SuccessResponse;
use App\Models\Bot;
use App\Models\BotChat;

class BotController extends Controller
{
    public function getBots(JsonRequest $request): ResourceCollection
    {
        return new ResourceCollection($request->user()->bots);
    }

    public function getBot(Bot $bot): BotResource
    {
        return new BotResource($bot);
    }

    public function createBot(CreateBotRequest $request): BotResource
    {
        $bot = new Bot();
        $bot->user()->associate($request->user());
        $bot->label = $request->label;
        $bot->token = $request->token;
        $bot->save();

        return new BotResource($bot);
    }

    public function deleteBot(Bot $bot): SuccessResponse
    {
        $bot->delete();

        return new SuccessResponse();
    }

    public function getBotChats(JsonRequest $request): ResourceCollection
    {
        return new ResourceCollection($request->user()->botChats);
    }

    public function getBotChat(BotChat $botChat): BotChatResource
    {
        return new BotChatResource($botChat);
    }

    public function createBotChat(CreateOrUpdateBotChatRequest $request): BotChatResource
    {
        return new BotChatResource($this->mergeBotChatRequest(new BotChat(), $request));
    }

    public function updateBotChat(BotChat $botChat, CreateOrUpdateBotChatRequest $request): BotChatResource
    {
        return new BotChatResource($this->mergeBotChatRequest($botChat, $request));
    }

    public function deleteBotChat(BotChat $botChat): SuccessResponse
    {
        $botChat->delete();

        return new SuccessResponse();
    }

    private function mergeBotChatRequest(BotChat $botChat, CreateOrUpdateBotChatRequest $request): BotChat
    {
        $botChat->bot_id = $request->bot;
        $botChat->label = $request->label;
        $botChat->chat_id = $request->chat;
        $botChat->save();

        return $botChat;
    }
}
