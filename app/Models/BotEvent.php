<?php declare(strict_types=1);

namespace App\Models;

use App\Enums\ChatEvent;
use App\Models\Traits\HasPhoto;
use App\Models\Traits\HasUser;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $event
 * @property string $message
 * @property string $photo_path
 * @property bool $active
 *
 * @method static self|Builder whereActive()
 * @method static self|Builder whereEvent(ChatEvent $event)
 */
class BotEvent extends BaseModel
{
    use HasUser,
        HasPhoto,
        SoftDeletes;

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

    public function getPhotoField(): string
    {
        return 'photo_path';
    }

    public function getDirName(): string
    {
        return 'events/' . sha1((string) $this->user->id);
    }
}
