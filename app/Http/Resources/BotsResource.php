<?php declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Bot;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BotsResource extends ResourceCollection
{
    /** @var Bot[]|Collection */
    public $resource;

    public function toArray($request): array
    {
        return $this->resource->map(static function (Bot $bot) use ($request) {
            return (new BotResource($bot))->toArray($request);
        });
    }
}
