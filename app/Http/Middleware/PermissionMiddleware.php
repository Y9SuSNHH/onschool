<?php

namespace App\Http\Middleware;

use App\Enums\UserRoleEnum;
use App\Http\Controllers\ResponseTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Illuminate\Support\Arr;

class PermissionMiddleware
{
    use ResponseTrait;

    protected Route $route;

    public function __construct(Route $route)
    {
        $this->route = $route;
    }

    public function handle(Request $request, Closure $next)
    {
        if (auth()->user()->active === 0) {
            return $this->errorResponse('Unauthorized action');
        }
        $role   = auth()->user()->role;
        $as     = explode('.', $this->route->getAction()['as']);
        $method = $as[2];
        foreach ($as as $i => $iValue) {
            if ($i > 2) {
                $method .= '.' . $iValue;
            }
        }
        if ($role === UserRoleEnum::USER) {
            $permission = ['students.destroy', 'users.destroy', 'users.update.active'];
            foreach ($permission as $key => $value) {
                if ($value === $method) {
                    return $this->errorResponse('Incorrect permission');
                }
            }
        }


        return $next($request);
    }
}
