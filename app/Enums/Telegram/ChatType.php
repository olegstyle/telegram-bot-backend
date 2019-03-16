<?php declare(strict_types=1);

namespace App\Enums\Telegram;

use OlegStyle\Enum\Enum;

/**
 * @method static static PRIVATE()
 * @method static static GROUP()
 * @method static static SUPER_GROUP()
 * @method static static CHANNEL()
 */
class ChatType extends Enum
{
    public const PRIVATE = 'private';
    public const GROUP = 'group';
    public const SUPER_GROUP = 'supergroup';
    public const CHANNEL = 'channel';

    public static function getSupported(): array
    {
        return [
            self::GROUP,
            self::SUPER_GROUP,
        ];
    }

    public function isSupported(): bool
    {
        return in_array($this->getValue(), self::getSupported(), true);
    }
}
