<?php declare(strict_types=1);

namespace App\Http\Requests\Bot;

use App\Http\Requests\Api\JsonRequest;
use App\Models\Bot;
use Illuminate\Validation\Rule;

/**
 * @property-read string $label
 * @property-read int $bot
 * @property-read string $chat
 */
class CreateOrUpdateBotChatRequest extends JsonRequest
{
    public function rules(): array
    {
        return [
            'label' => array_merge(['required'], $this->getCommonRules()->getBotLabel()),
            'bot' => ['required', Rule::exists(Bot::getTableName(), Bot::getModelKeyName())->where('e_user', $this->user()->id)],
            'chat' => ['required', 'string', 'min:1', 'max:191'],
        ];
    }
}
