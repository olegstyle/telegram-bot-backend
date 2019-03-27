<?php declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\BaseModel;
use App\Models\BotChat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int bot_chat_id
 * @property-read BotChat chat()
 * @method static static|Builder whereBotChat(BotChat $botChat)
 * @mixin BaseModel
 */
trait HasBotChat
{
    public function chat(): BelongsTo
    {
        return $this->belongsTo(BotChat::class);
    }

    public function scopeWhereBot(Builder $builder, BotChat $botChat): Builder
    {
        return $builder->where('bot_chat_id', $botChat->id);
    }
}
