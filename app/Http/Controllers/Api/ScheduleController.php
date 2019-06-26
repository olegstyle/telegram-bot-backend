<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Enums\Action;
use App\Http\Controllers\Controller;
use App\Http\Requests\Api\JsonRequest;
use App\Http\Requests\Schedule\CreateOrUpdateScheduleRequest;
use App\Http\Resources\ResourceCollection;
use App\Http\Resources\ScheduleResource;
use App\Http\Responses\SuccessResponse;
use App\Models\Schedules\Schedule;
use App\Models\Schedules\ScheduleAction;
use Illuminate\Support\Facades\DB;

class ScheduleController extends Controller
{
    public function all(JsonRequest $request): ResourceCollection
    {
        return new ResourceCollection($request->user()->schedules()->orderDescById()->with(['action', 'botChats'])->get());
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

        $parts = explode(' ', $request->expression);
        $schedule->minutes = $parts[0];
        $schedule->hours = $parts[1];
        $schedule->day = $parts[2];
        $schedule->month = $parts[3];
        $schedule->week_day = $parts[4];

        $action = $schedule->action;
        if (!$action) {
            $action = new ScheduleAction();
        }
        $action->action = Action::POST;
        $action->action_id = $request->actionId;

        DB::beginTransaction();

        $schedule->save();
        $action->schedule()->associate($schedule);
        $action->save();
        $this->replaceScheduleChats($schedule, $request->botChats);

        $schedule->load(['action', 'botChats']);

        DB::commit();

        return $schedule;
    }

    private function replaceScheduleChats(Schedule $schedule, array $botChatIds): void
    {
        $botChatIds = array_map('intval', $botChatIds);
        $schedule->load('botChats');
        $existIds = $schedule->botChats->pluck('id')->toArray();

        Schedule::removeBotChats(array_filter($existIds, static function (int $id) use ($botChatIds) {
            return !in_array($id, $botChatIds, true);
        }));

        $schedule->bulkInsertBotChats(array_filter($botChatIds, static function (int $id) use ($existIds) {
            return !in_array($id, $existIds, true);
        }));
    }
}
