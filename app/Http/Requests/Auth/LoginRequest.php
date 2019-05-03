<?php declare(strict_types=1);

namespace App\Http\Requests\Auth;

use App\Http\Requests\Api\JsonRequest;

/**
 * @property string $email
 * @property string $password
 */
class LoginRequest extends JsonRequest
{
    public function rules(): array
    {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'min:6'],
        ];
    }
}
