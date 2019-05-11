<?php declare(strict_types=1);

namespace App\Http\Responses\Error;

use App\Enums\ErrorTag;
use Illuminate\Http\Response;

class ForbiddenResponse extends ErrorResponse
{
    public function __construct(?ErrorTag $errorCode = null)
    {
        parent::__construct($errorCode ?? ErrorTag::UNAUTHORIZED_ACTION(), Response::HTTP_FORBIDDEN);
    }
}
