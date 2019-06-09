<?php declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Schedules\Schedule;
use Illuminate\Database\Eloquent\Collection;

class SchedulesResource extends ResourceCollection
{
    /** @var Schedule[]|Collection */
    public $resource;

    public function toArray($request): array
    {
        return $this->resource->map(static function (Schedule $schedule) use ($request) {
            return (new ScheduleResource($schedule))->toArray($request);
        });
    }
}
