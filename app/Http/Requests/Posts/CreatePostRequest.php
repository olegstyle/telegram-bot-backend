<?php declare(strict_types=1);

namespace App\Http\Requests\Posts;

use App\Http\Requests\Api\JsonRequest;
use Illuminate\Http\UploadedFile;

/**
 * @property-read string $title
 * @property-read string $message
 * @property-read UploadedFile|null $photo
 * @property-read string|null $active
 */
class CreatePostRequest extends JsonRequest
{
    public function rules(): array
    {
        return [
            'title' => array_merge(['required'], $this->getCommonRules()->getPostTitle()),
            'message' => array_merge(['required'], $this->getCommonRules()->getPostMessage()),
            'photo' => array_merge(['nullable'], $this->getCommonRules()->getPostPhoto()),
            'active' => ['nullable', 'boolean'],
        ];
    }

    public function isActive(): bool
    {
        return (bool) $this->active;
    }
}
