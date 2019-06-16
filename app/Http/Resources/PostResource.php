<?php declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Post;
use Illuminate\Http\Resources\Json\JsonResource;

class PostResource extends JsonResource
{
    /** @var Post */
    public $resource;

    public function __construct(Post $resource)
    {
        parent::__construct($resource);
    }

    public function toArray($request): array
    {
        return [
            'id' => $this->resource->id,
            'title' => $this->resource->title,
            'message' => $this->resource->message,
            'photoPath' => $this->resource->getPhotoHttpPath(),
            'active' => $this->resource->active,
        ];
    }
}
