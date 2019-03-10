<?php declare(strict_types=1);

namespace App\Models;

/**
 * TODO
 * @property string id
 * @property string chat_id
 * @property string chat_label
 */
class BotChat extends BaseModel
{
    public $id = '1';
    public $chat_id = 'bot_testing_group';
    public $chat_label = 'Testing';
}
