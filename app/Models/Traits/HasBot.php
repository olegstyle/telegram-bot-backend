<?php declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\BaseModel;
use App\Models\Bot;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int bot_id
 * @property-read Bot bot()
 * @method static static|Builder whereBot(Bot $bot)
 * @mixin BaseModel
 */
trait HasBot
{
    public function bot(): BelongsTo
    {
        return $this->belongsTo(Bot::class);
    }

    public function scopeWhereBot(Builder $builder, Bot $bot): Builder
    {
        return $builder->where('bot_id', $bot->id);
    }
}
