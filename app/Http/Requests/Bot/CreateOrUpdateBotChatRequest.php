<?php declare(strict_types=1);

namespace App\Http\Requests\Bot;

use App\Http\Requests\Api\JsonRequest;
use App\Models\Bot;

/**
 * @property-read string $label
 * @property-read string $bot
 * @property-read string $chat
 */
class CreateOrUpdateBotChatRequest extends JsonRequest
{
    public function rules(): array
    {
        return [
            'label' => array_merge(['required'], $this->getCommonRules()->getBotLabel()),
            'bot' => ['required', 'numeric', $this->modelExists(app(Bot::class), $this->user())],
            'chat' => ['required', 'string', 'min:1', 'max:191'],
        ];
    }
}
