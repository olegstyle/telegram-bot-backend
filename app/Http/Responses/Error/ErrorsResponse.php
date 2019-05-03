<?php declare(strict_types=1);

namespace App\Http\Responses\Error;

use App\Enums\ErrorTag;
use Illuminate\Http\JsonResponse;

class ErrorsResponse extends JsonResponse
{
    public function __construct(array $messages = [], int $status = self::HTTP_INTERNAL_SERVER_ERROR, ?ErrorTag $errorCode = null)
    {
        parent::__construct([
            'error' => true,
            'tag' => ($errorCode ?? ErrorTag::UNKNOWN_ERROR())->getValue(),
            'errors' => $messages,
        ], $status);
    }
}
