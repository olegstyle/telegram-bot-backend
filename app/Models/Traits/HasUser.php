<?php declare(strict_types=1);

namespace App\Models\Traits;

use App\Models\BaseModel;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int user_id
 * @property-read User user()
 * @method static static|Builder whereUser(User $user)
 * @method static static|Builder whereNotUser(User $user)
 * @mixin BaseModel
 */
trait HasUser
{
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopeWhereUser(Builder $builder, User $user): Builder
    {
        return $builder->where('user_id', $user->id);
    }

    public function scopeWhereNotUser(Builder $builder, User $user): Builder
    {
        return $builder->where('user_id', '!=', $user->id);
    }
}
