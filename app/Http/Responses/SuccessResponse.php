<?php declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\JsonResponse;

class SuccessResponse extends JsonResponse
{
    public function __construct(int $status = self::HTTP_OK)
    {
        parent::__construct(['success' => true], $status);
    }
}
