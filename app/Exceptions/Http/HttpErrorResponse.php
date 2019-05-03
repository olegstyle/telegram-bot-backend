<?php declare(strict_types=1);

namespace App\Exceptions\Http;

use App\Enums\ErrorTag;
use App\Http\Responses\Error\ErrorResponse;
use Illuminate\Http\Exceptions\HttpResponseException;

class HttpErrorResponse extends HttpResponseException
{
    public function __construct(?ErrorTag $error = null, ?int $statusCode = null)
    {
        parent::__construct(new ErrorResponse($error, $statusCode));
    }
}
