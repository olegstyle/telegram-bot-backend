<?php declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Bot;
use App\Models\BotChat;
use Illuminate\Http\Resources\Json\JsonResource;

class BotResource extends JsonResource
{
    /** @var Bot */
    public $resource;

    public function __construct(Bot $resource)
    {
        parent::__construct($resource);
    }

    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'label' => $this->resource->label,
            'chats' => $this->resource->chats->map(static function (BotChat $chat) use ($request) {
                return (new BotChatResource($chat))->toArray($request);
            })->toArray(),
        ];
    }
}
