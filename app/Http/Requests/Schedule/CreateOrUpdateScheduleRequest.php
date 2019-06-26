<?php declare(strict_types=1);

namespace App\Http\Requests\Schedule;

use App\Http\Requests\Api\JsonRequest;
use App\Http\Requests\Rules\CronExpression;
use App\Models\BotChat;
use App\Models\Post;

/**
 * @property-read string $title
 * @property-read string $expression
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
            'botChats' => ['bail', 'required', 'array'],
            'botChats.*' => ['required', 'numeric', $this->modelExists(app(BotChat::class), $this->user())],
            'active' => ['nullable', 'boolean'],
        ];
    }

    public function isActive(): bool
    {
        return (bool) $this->active;
    }
}
