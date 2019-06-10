<?php declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Bot;
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
        ];
    }
}
