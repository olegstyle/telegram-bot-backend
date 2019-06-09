<?php declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\BotChat;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BotChatsResource extends ResourceCollection
{
    /** @var BotChat[]|Collection */
    public $resource;

    public function toArray($request): array
    {
        return $this->resource->map(static function (BotChat $botChat) use ($request) {
            return (new BotChatResource($botChat))->toArray($request);
        });
    }
}
