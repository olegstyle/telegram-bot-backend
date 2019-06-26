<?php declare(strict_types=1);

namespace App\Http\Requests\Rules;

use App\Models\Bot;
use App\Models\BotChat;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Database\Eloquent\Builder;

class HasAccessToBotChat implements Rule
{
    private $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function passes($attribute, $data): bool
    {
        return !BotChat::whereIds(array_map('intval', $data))
            ->whereHas('bot', function (Builder $builder) {
                /** @var Bot $builder */
                return $builder->whereNotUser($this->user);
            })->exists();
    }

    public function message(): string
    {
        return trans('One or more selected bot chats are not your.');
    }
}
