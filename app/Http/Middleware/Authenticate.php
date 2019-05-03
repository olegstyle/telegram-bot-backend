<?php

namespace App\Http\Middleware;

use App\Exceptions\Http\Auth\UnauthorizedAction;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    protected function redirectTo($request)
    {
        throw new UnauthorizedAction();
    }
}
