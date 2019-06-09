<?php declare(strict_types=1);

namespace App\Http\Requests\Api;

use App\Http\Requests\CommonRules;
use App\Http\Responses\Error\ValidationErrorResponse;
use App\Models\User;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

/**
 * @method User user()
 */
class JsonRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [];
    }

    protected function getCommonRules(): CommonRules
    {
        return app(CommonRules::class);
    }

    public function withValidator(Validator $validator): void
    {
        if (count($this->rulesForNullable()) === 0) {
            return;
        }

        $validator->after(function (Validator $validator) {
            foreach ($this->rulesForNullable() as $attribute => $rule) {
                if ($rule->passes($attribute, $this->input($attribute))) {
                    continue;
                }
                $validator->errors()->add($attribute, $rule->message());
            }
        });
    }

    /** @return Rule[] [attribute => rule] */
    public function rulesForNullable(): array
    {
        return [];
    }

    protected function failedValidation(Validator $validator): void
    {
        throw new HttpResponseException(new ValidationErrorResponse($validator->errors()->toArray()));
    }
}
