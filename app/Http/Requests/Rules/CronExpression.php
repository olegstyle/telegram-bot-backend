<?php declare(strict_types=1);

namespace App\Http\Requests\Rules;

use Exception;
use Illuminate\Contracts\Validation\Rule;

class CronExpression implements Rule
{
    public function passes($attribute, $value): bool
    {
        try {
            \Cron\CronExpression::factory($value);
        } catch (Exception $exception) {
            return false;
        }

        return true;
    }

    public function message(): string
    {
        return trans('The expression is invalid!');
    }
}
