<?php declare(strict_types=1);

namespace App\Models\Schedules;

use App\Models\BaseModel;
use App\Models\BotChat;
use App\Models\Traits\HasUser;
use Cron\CronExpression;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Fields
 * @property string $title
 * @property bool $active
 * @property string|null $minutes
 * @property string|null $hours
 * @property string|null $day
 * @property string|null $month
 * @property string|null $week_day
 * @property-read string $inlineExpression
 * @property-read CronExpression $expression
 *
 * Relations
 * @property-read ScheduleAction $action
 * @property-read Collection|BotChat[] $botChats
 *
 * Scopes
 * @method static Builder|static whereActive()
 */
class Schedule extends BaseModel
{
    use HasUser,
        SoftDeletes;

    protected $table = 'schedules';

    protected $casts = ['active' => 'bool'];

    public function getInlineExpressionAttribute(): string
    {
        return ($this->minutes ?? '*') . ' ' . ($this->hours ?? '*') . ' ' . ($this->day ?? '*') . ' ' . ($this->month ?? '*') . ' ' . ($this->week_day ?? '*');
    }

    public function getExpressionAttribute(): CronExpression
    {
        return CronExpression::factory($this->inlineExpression);
    }

    public function action(): HasOne
    {
        return $this->hasOne(ScheduleAction::class, 'schedule_id');
    }

    public function botChats(): BelongsToMany
    {
        return $this->belongsToMany(BotChat::class, 'schedule_to_bot_chats');
    }

    public function scopeWhereActive(Builder $builder): Builder
    {
        return $builder->where('active', true);
    }

    public function explodeData(?string $data): ?array
    {
        return $data ? array_map('trim', explode(',', $data)) : null;
    }

    public function implodeData(?array $data): ?string
    {
        return $data ? implode(',', $data) : null;
    }

    public function getMinutes(): ?array
    {
        return $this->explodeData($this->minutes);
    }

    public function getHours(): ?array
    {
        return $this->explodeData($this->hours);
    }

    public function getDay(): ?array
    {
        return $this->explodeData($this->day);
    }

    public function getMonth(): ?array
    {
        return $this->explodeData($this->month);
    }

    public function getWeekDay(): ?array
    {
        return $this->explodeData($this->week_day);
    }
}
