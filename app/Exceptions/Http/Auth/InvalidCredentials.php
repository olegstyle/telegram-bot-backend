<?php declare(strict_types=1);

namespace App\Exceptions\Http\Auth;

use App\Enums\ErrorTag;
use App\Exceptions\Http\HttpErrorResponse;
use Illuminate\Http\Response;

class InvalidCredentials extends HttpErrorResponse
{
    public function __construct()
    {
        parent::__construct(ErrorTag::INVALID_CREDENTIALS(), Response::HTTP_UNAUTHORIZED);
    }
}
