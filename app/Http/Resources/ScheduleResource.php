<?php declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Schedules\Schedule;
use Illuminate\Http\Resources\Json\JsonResource;

class ScheduleResource extends JsonResource
{
    /** @var Schedule */
    public $resource;

    public function __construct(Schedule $resource)
    {
        parent::__construct($resource);
    }

    public function toArray($request): array
    {
        /** @noinspection PhpParamsInspection */
        $action = $this->resource->action && $this->resource->action->actionModel ?
            (new PostResource($this->resource->action->actionModel))->toArray($request) :
            null;

        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'active' => $this->resource->active,
            'expression' => $this->resource->getInlineExpressionAttribute(),
            'actionType' => $this->resource->action->getAction()->getValue(),
            'botChats' => (new ResourceCollection($this->resource->botChats))->toArray($request),
            'action' => $action,
        ];
    }
}
