<?php declare(strict_types=1);

namespace App\Enums;

use OlegStyle\Enum\Enum;

/**
 * @method static static MODEL_NOT_FOUND()
 * @method static static NOT_FOUND()
 * @method static static VALIDATION_FAILED()
 * @method static static UNKNOWN_ERROR()
 * @method static static SERVER_ERROR()
 *
 * Authorization
 * @method static static UNAUTHORIZED_ACTION()
 * @method static static INVALID_CREDENTIALS()
 */
final class ErrorTag extends Enum
{
    // Common
    public const MODEL_NOT_FOUND = 'model_not_found';
    public const NOT_FOUND = 'not_found';
    public const VALIDATION_FAILED = 'validation_failed';
    public const SERVER_ERROR = 'server_error';
    public const UNKNOWN_ERROR = 'unknown_error';

    // Authorization
    public const UNAUTHORIZED_ACTION = 'unauthorized_action';
    public const INVALID_CREDENTIALS = 'invalid_credentials';
}
