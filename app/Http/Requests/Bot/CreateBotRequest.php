<?php declare(strict_types=1);

namespace App\Http\Requests\Bot;

use App\Http\Requests\Api\JsonRequest;

/**
 * @property-read string $label
 * @property-read string $token
 */
class CreateBotRequest extends JsonRequest
{
    public function rules(): array
    {
        return [
            'label' => array_merge(['required'], $this->getCommonRules()->getBotLabel()),
            'token' => ['required', 'string', 'min:1'],
        ];
    }
}
