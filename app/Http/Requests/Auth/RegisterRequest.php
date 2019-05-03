<?php declare(strict_types=1);

namespace App\Http\Requests\Auth;

use Illuminate\Validation\Rule;

/**
 * @property string $name
 */
class RegisterRequest extends LoginRequest
{
    public function rules(): array
    {
        $rules = parent::rules();
        $rules['name'] = ['required', 'string', 'min:3'];
        $rules['email'] = array_merge($rules['email'], [Rule::unique('users', 'email')]);

        return $rules;
    }
}
