<?php declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\BotChat;
use Illuminate\Http\Resources\Json\JsonResource;

class BotChatResource extends JsonResource
{
    /** @var BotChat */
    public $resource;
    public $returnBots;

    public function __construct(BotChat $resource, bool $returnBots = true)
    {
        parent::__construct($resource);
        $this->returnBots = $returnBots;
    }

    public function toArray($request): array
    {
        $data = [
            'id' => $this->resource->id,
            'label' => $this->resource->label,
            'chat' => $this->resource->chat_id,
        ];

        if ($this->returnBots) {
            $data['bot'] = (new BotResource($this->resource->bot, false))->toArray($request);
        }

        return $data;
    }
}
