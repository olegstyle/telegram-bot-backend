<?php declare(strict_types=1);

namespace App\Http\Resources;

use App\Models\Post;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Resources\Json\ResourceCollection as BaseResourceCollection;
use Illuminate\Pagination\AbstractPaginator;
use App\Http\Responses\PaginatedResourceResponse;

class ResourceCollection extends BaseResourceCollection
{
    /** @var Collection */
    public $resource;

    public function __construct(Collection $resource)
    {
        parent::__construct($resource);
    }

    public function toArray($request): array
    {
        if ($this->resource->isEmpty()) {
            return [];
        }

        $resourceClass = str_replace(class_basename($this), '', self::class) .
             class_basename($this->resource->first()).
             'Resource';

        return $this->resource->map(static function ($model) use ($request, $resourceClass) {
            /** @noinspection PhpUndefinedMethodInspection */
            return (new $resourceClass($model))->toArray($request);
        })->toArray();
    }

    public function toResponse($request)
    {
        return $this->resource instanceof AbstractPaginator
            ? (new PaginatedResourceResponse($this))->toResponse($request)
            : parent::toResponse($request);
    }
}
