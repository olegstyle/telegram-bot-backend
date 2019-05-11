<?php declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\User;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    /** @var User */
    public $resource;

    public function __construct(User $resource)
    {
        parent::__construct($resource);
    }

    public function toArray($request): array
    {
        return [
            'email' => $this->resource->email,
            'name' => $this->resource->name,
        ];
    }
}
