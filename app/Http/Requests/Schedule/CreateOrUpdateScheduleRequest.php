<?php declare(strict_types=1);

namespace App\Http\Requests\Schedule;

use App\Http\Requests\Api\JsonRequest;
use App\Http\Requests\Rules\CronExpression;
use App\Http\Requests\Rules\HasAccessToBotChat;
use App\Models\Post;

/**
 * @property-read string $title
 * @property-read string $expression
 * @property-read array $botChats
 * @property-read string $actionId
 * @property-read string|null $active
 */
class CreateOrUpdateScheduleRequest extends JsonRequest
{
    public function rules(): array
    {
        return [
            'title' => array_merge(['required'], $this->getCommonRules()->getBotLabel()),
            'expression' => ['required', 'string', new CronExpression()],
            'actionId' => ['nullable', 'numeric', $this->modelExists(app(Post::class), $this->user())],
            'botChats' => ['bail', 'required', 'array', 'min:1', new HasAccessToBotChat($this->user())],
            'botChats.*' => ['required', 'numeric'],
            'active' => ['nullable', 'boolean'],
        ];
    }

    public function isActive(): bool
    {
        return (bool) $this->active;
    }
}
