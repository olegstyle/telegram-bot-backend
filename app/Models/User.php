<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Schedules\Schedule;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

/**
 * Fields
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string|null $password
 * @property Carbon $email_verified_at
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * Appends
 * @property-read Bot[]|Collection $bots
 * @property-read BotChat[]|Collection $botChats
 * @property-read Post[]|Collection $posts
 * @property-read Schedule[]|Collection $schedules
 */
class User extends Authenticatable
{
    use HasApiTokens,
        Notifiable,
        SoftDeletes;

    protected $table = 'users';
    protected $fillable = ['name', 'email', 'password'];
    protected $hidden = ['password', 'remember_token'];
    protected $casts = ['email_verified_at' => 'datetime'];

    /** @return Bot|HasMany */
    public function bots(): HasMany
    {
        return $this->hasMany(Bot::class, $this->getForeignKey());
    }

    /** @return BotChat|HasManyThrough */
    public function botChats(): HasManyThrough
    {
        return $this->hasManyThrough(BotChat::class, Bot::class);
    }

    /** @return Post|HasMany */
    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    /** @return Schedule|HasMany */
    public function schedules(): HasMany
    {
        return $this->hasMany(Schedule::class);
    }
}
