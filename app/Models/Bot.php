<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasUser;
use App\Models\Traits\HasEncryptedFields;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $label
 * @property string $token
 *
 * @property-read Collection|BotChat[] $chats
 */
class Bot extends BaseModel
{
    use HasEncryptedFields,
        HasUser,
        SoftDeletes;

    protected $table = 'bots';

    public function chats(): HasMany
    {
        return $this->hasMany(BotChat::class);
    }

    protected function getEncryptedFields(): array
    {
        return ['token'];
    }
}
