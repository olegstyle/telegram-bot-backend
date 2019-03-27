<?php declare(strict_types=1);

namespace App\Models;

use App\Enums\ChatEvent;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $event
 * @property string $message
 * @property string $photo_path -- TODO
 * @property bool $active
 *
 * @method static self|Builder whereActive()
 * @method static self|Builder whereEvent(ChatEvent $event)
 */
class BotEvent extends BaseModel
{
    protected $table = 'bot_events';

    protected $casts = ['active' => 'bool'];

    public function scopeWhereActive(Builder $builder): Builder
    {
        return $builder->where('active', true);
    }

    public function scopeWhereEvent(Builder $builder, ChatEvent $event): Builder
    {
        return $builder->where('event', $event->getValue());
    }

    public function getEvent(): ChatEvent
    {
        return new ChatEvent($this->event);
    }
}
