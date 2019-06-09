<?php declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;

class PostsResource extends ResourceCollection
{
    /** @var Post[]|Collection */
    public $resource;

    public function toArray($request): array
    {
        return $this->resource->map(static function (Post $post) use ($request) {
            return (new PostResource($post))->toArray($request);
        });
    }
}
