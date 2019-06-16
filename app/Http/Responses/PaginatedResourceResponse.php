<?php declare(strict_types=1);

namespace App\Http\Responses;

use Illuminate\Http\Resources\Json\PaginatedResourceResponse as BasePaginatedResourceResponse;
use Illuminate\Support\Str;

class PaginatedResourceResponse extends BasePaginatedResourceResponse
{
    protected function paginationInformation($paginated)
    {
        return $this->convertKeysToCamelCase(parent::paginationInformation($paginated));
    }

    protected function convertKeysToCamelCase($data)
    {
        if (false === is_array($data)) {
            return $data;
        }
        $converted = [];
        foreach ($data as $key => $value) {
            $converted[Str::camel($key)] = $this->convertKeysToCamelCase($value);
        }

        return $converted;
    }
}
