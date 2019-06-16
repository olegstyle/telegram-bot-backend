<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasBot;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $label
 * @property string $chat_id
 *
 * @property-read BotChat[]|Collection $events
 * @property-read BotChatSetting[]|Collection $settings
 */
class BotChat extends BaseModel
{
    use HasBot,
        SoftDeletes;

    protected $table = 'bot_chats';

    /** @return BelongsToMany|BotEvent */
    public function events(): BelongsToMany
    {
        return $this->belongsToMany(BotEvent::class, 'bot_events_to_chats');
    }

    /** @return HasMany|BotChatSetting */
    public function settings(): HasMany
    {
        return $this->hasMany(BotChatSetting::class);
    }
}
