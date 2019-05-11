<?php declare(strict_types=1);

namespace App\Http\Responses\Error;

use App\Enums\ErrorTag;

class ErrorResponse extends ErrorsResponse
{
    public function __construct(ErrorTag $errorCode, int $status = self::HTTP_INTERNAL_SERVER_ERROR)
    {
        parent::__construct([], $status, $errorCode);
    }
}
