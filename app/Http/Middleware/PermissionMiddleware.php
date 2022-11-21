<?php

namespace App\Http\Middleware;

use App\Enums\UserRoleEnum;
use App\Http\Controllers\ResponseTrait;
use App\Models\User;
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
        $role = auth()->user()->role;
        $id   = $this->route->id;
        if (!is_numeric($id) && !is_numeric($role)) {
            return $this->errorResponse('Incorrect id');
        }
        $as     = explode('.', $this->route->getAction()['as']);
        $method = $as[1];
        foreach ($as as $i => $iValue) {
            if ($i > 1) {
                $method .= '.' . $iValue;
            }
        }
        if ($role === UserRoleEnum::USER) {
            $permission = ['students.destroy', 'users.destroy'];
            foreach ($permission as $key => $value) {
                if ($value === $method) {
                    return $this->errorResponse('Incorrect permission');
                }
            }
            if ($method === 'users.update') {
                $user = User::query()->find($id);
                if (!$user) {
                    return $this->errorResponse('This user does not exist');
                }

                if ($user->role !== $role) {
                    return $this->errorResponse('Incorrect permission');
                }
            }
        }

        return $next($request);
    }
}
