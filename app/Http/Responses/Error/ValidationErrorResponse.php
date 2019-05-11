<?php declare(strict_types=1);

namespace App\Http\Responses\Error;

use App\Enums\ErrorTag;

class ValidationErrorResponse extends ErrorsResponse
{
    public function __construct(array $errors = [], int $status = self::HTTP_UNPROCESSABLE_ENTITY)
    {
        parent::__construct($errors, $status, ErrorTag::VALIDATION_FAILED());
    }
}
