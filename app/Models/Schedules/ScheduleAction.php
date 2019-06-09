<?php declare(strict_types=1);

namespace App\Models\Schedules;

use App\Enums\Action;
use App\Models\BaseModel;
use App\Models\Post;
use App\Models\Traits\HasSchedule;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Fields
 * @property string $action
 * @property int $action_id
 * @property Model|null $actionModel
 */
class ScheduleAction extends BaseModel
{
    use HasSchedule,
        SoftDeletes;

    protected $table = 'schedule_actions';

    public function getActionModelAttribute(): ?Model
    {
        if ($this->getAction()->getValue() === Action::POST) {
            return Post::query()->find($this->action_id);
        }

        return null;
    }

    public function getAction(): Action
    {
        return new Action($this->action);
    }
}
