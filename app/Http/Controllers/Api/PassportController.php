<?php declare(strict_types=1);

namespace App\Http\Controllers\Api;

use App\Enums\TokenName;
use App\Exceptions\Http\Auth\InvalidCredentials;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Resources\TokenResource;
use App\Models\User;

class PassportController extends Controller
{
    public function register(RegisterRequest $request): TokenResource
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->save();

        $token = $user->createToken(TokenName::API)->accessToken;

        return new TokenResource($token);
    }

    public function login(LoginRequest $request): TokenResource
    {
        if (!auth()->attempt(['email' => $request->email, 'password' => $request->password])) {
            throw new InvalidCredentials();
        }
        /** @var User $user */
        $user = auth()->user();

        return new TokenResource($user->createToken(TokenName::API)->accessToken);
    }
}
