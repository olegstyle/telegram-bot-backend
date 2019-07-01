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
use Illuminate\Support\Facades\DB;

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

    public const BOT_CHAT_BELONGS_TABLE = 'schedule_to_bot_chats';

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
        return $this->belongsToMany(BotChat::class, self::BOT_CHAT_BELONGS_TABLE);
    }

    public function scopeWhereActive(Builder $builder): Builder
    {
        return $builder->where('active', true);
    }

    public static function removeBotChats(array $botChatIds): void
    {
        if (empty($botChatIds)) {
            return;
        }
        DB::table(self::BOT_CHAT_BELONGS_TABLE)
            ->whereIn(BotChat::getModelForeignKey(), $botChatIds)
            ->delete();
    }

    public function bulkInsertBotChats(array $botChatIds): void
    {
        if (empty($botChatIds)) {
            return;
        }

        $data = [];
        foreach ($botChatIds as $botChatId) {
            $data[] = [
                $this->getForeignKey() => $this->id,
                BotChat::getModelForeignKey() => $botChatId,
            ];
        }

        DB::table(self::BOT_CHAT_BELONGS_TABLE)->insert($data);
    }
}
