<?php declare(strict_types=1);

namespace App\Models;

use App\Models\Traits\HasBot;

/**
 * @property string id
 * @property string $label
 * @property string chat_id
 */
class BotChat extends BaseModel
{
    use HasBot;

    protected $table = 'bot_chats';
}
