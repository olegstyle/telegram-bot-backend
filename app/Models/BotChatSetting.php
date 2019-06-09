<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasBotChat;
use Illuminate\Database\Eloquent\Builder;
use App\Enums\BotChatSettingName;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property string $setting
 * @property string|null $value
 *
 * @method static static|Builder whereSetting(BotChatSettingName $setting)
 */
class BotChatSetting extends BaseModel
{
    use HasBotChat,
        SoftDeletes;

    protected $table = 'bot_chat_settings';

    public function scopeWhereSetting(Builder $builder, BotChatSettingName $setting): Builder
    {
        return $builder->where('setting', $setting->getValue());
    }

    public function getSetting(): BotChatSettingName
    {
        return new BotChatSettingName($this->setting);
    }
}
