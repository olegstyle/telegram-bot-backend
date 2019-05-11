<?php declare(strict_types=1);

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class TokenResource extends JsonResource
{
    /** @var string */
    public $resource;

    public function __construct(string $token)
    {
        parent::__construct($token);
    }

    public function toArray($request): array
    {
        return ['accessToken' => $this->resource];
    }
}
