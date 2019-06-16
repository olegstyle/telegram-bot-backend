<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\JsonRequest;
use App\Http\Requests\Schedule\CreateOrUpdateScheduleRequest;
use App\Http\Resources\ResourceCollection;
use App\Http\Resources\ScheduleResource;
use App\Http\Responses\SuccessResponse;
use App\Models\Schedules\Schedule;

class ScheduleController extends Controller
{
    public function all(JsonRequest $request): ResourceCollection
    {
        return new ResourceCollection($request->user()->schedules);
    }

    public function get(Schedule $schedule): ScheduleResource
    {
        return new ScheduleResource($schedule);
    }

    public function create(CreateOrUpdateScheduleRequest $request): ScheduleResource
    {
        return new ScheduleResource($this->mergeSchedule(new Schedule(), $request));
    }

    public function update(Schedule $schedule, CreateOrUpdateScheduleRequest $request): ScheduleResource
    {
        return new ScheduleResource($this->mergeSchedule($schedule, $request));
    }

    public function delete(Schedule $schedule): SuccessResponse
    {
        $schedule->delete();

        return new SuccessResponse();
    }

    private function mergeSchedule(Schedule $schedule, CreateOrUpdateScheduleRequest $request): Schedule
    {
        $schedule->user()->associate($request->user());
        $schedule->title = $request->title;
        $schedule->active = $request->isActive();
        $schedule->minutes = $schedule->implodeData($request->getMinutes());
        $schedule->hours = $schedule->implodeData($request->getHours());
        $schedule->day = $schedule->implodeData($request->getDay());
        $schedule->month = $schedule->implodeData($request->getMonth());
        $schedule->week_day = $schedule->implodeData($request->getWeekDay());
        $schedule->save();

        return $schedule;
    }
}
