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
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'active' => $this->resource->active,
            'minutes' => $this->resource->getMinutes(),
            'hours' => $this->resource->getHours(),
            'day' => $this->resource->getDay(),
            'month' => $this->resource->getMonth(),
            'weekDay' => $this->resource->getWeekDay(),
        ];
    }
}
