<?php declare(strict_types=1);

namespace App\Enums;

use OlegStyle\Enum\Enum;

/**
 * @method static static MARKDOWN()
 */
final class ParseMode extends Enum
{
    public const MARKDOWN = 'Markdown';

    public static function getDefault(): self
    {
        return self::MARKDOWN();
    }
}
