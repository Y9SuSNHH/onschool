<?php

namespace App\Http\Middleware;

use App\Enums\UserRoleEnum;
use App\Http\Controllers\ResponseTrait;
use Closure;
use Illuminate\Http\Request;

class JwtAdminMiddleware
{
    use ResponseTrait;

    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->role !== UserRoleEnum::ADMIN) {
            return $this->errorResponse('Incorrect permission');
        }
        return $next($request);
    }
}
