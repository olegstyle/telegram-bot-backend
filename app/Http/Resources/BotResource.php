<?php declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Bot;
use Illuminate\Http\Resources\Json\JsonResource;

class BotResource extends JsonResource
{
    /** @var Bot */
    public $resource;
    public $returnChats;

    public function __construct(Bot $resource, bool $returnChats = true)
    {
        parent::__construct($resource);
        $this->returnChats = $returnChats;
    }

    public function toArray($request): array
    {
        $data = [
            'id' => $this->resource->id,
            'label' => $this->resource->label,
        ];
        if (!$this->returnChats) {
            return $data;
        }

        $chats = [];
        foreach ($this->resource->chats as $chat) {
            $chats[] = (new BotChatResource($chat, false))->toArray($request);
        }
        $data['chats'] = $chats;

        return $data;
    }
}
