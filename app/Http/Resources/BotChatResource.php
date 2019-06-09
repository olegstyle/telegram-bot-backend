<?php declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\BotChat;
use Illuminate\Http\Resources\Json\JsonResource;

class BotChatResource extends JsonResource
{
    /** @var BotChat */
    public $resource;

    public function __construct(BotChat $resource)
    {
        parent::__construct($resource);
    }

    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'label' => $this->resource->label,
            'chat' => $this->resource->chat_id,
            'bot' => (new BotResource($this->resource->bot))->toArray($request),
        ];
    }
}
