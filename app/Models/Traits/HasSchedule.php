<?php declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\BaseModel;
use App\Models\Schedules\Schedule;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int schedule_id
 * @property-read Schedule schedule()
 * @method static Builder|static whereSchedule(Schedule $schedule)
 * @mixin BaseModel
 */
trait HasSchedule
{
    public function schedule(): BelongsTo
    {
        return $this->belongsTo(Schedule::class);
    }

    public function scopeWhereSchedule(Builder $builder, Schedule $schedule): Builder
    {
        return $builder->where('schedule_id', $schedule->id);
    }
}
