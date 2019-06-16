<?php declare(strict_types=1);

namespace App\Http\Requests\Posts;

/**
 * @property-read string|null $title
 * @property-read string|null $message
 */
class UpdatePostRequest extends CreatePostRequest
{
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'title' => array_merge(['nullable'], $this->getCommonRules()->getPostTitle()),
            'message' => array_merge(['nullable'], $this->getCommonRules()->getPostMessage())
        ]);
    }
}
