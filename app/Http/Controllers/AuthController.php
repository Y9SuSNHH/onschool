<?php

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AuthController extends Controller
{
    use ResponseTrait;

    public function __construct()
    {
//        $this->middleware('auth:api', ['except' => ['login']]);
    }

    public function me(): JsonResponse
    {
        return $this->successResponse(auth()->payload());
    }

    public function login(Request $request): JsonResponse
    {
        $credentials = $request->only('username', 'password');
        $token       = $this->guard()->attempt($credentials);
        if ($token) {
            return $this->respondWithToken($token);
        }

        return $this->errorResponse('Unauthorized', 401);
    }

    public function logout(): JsonResponse
    {
        $this->guard()->logout();

        return $this->successResponse([], 'Successfully logged out');
    }

    protected function respondWithToken($token): JsonResponse
    {
        return $this->successResponse([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => $this->guard()->factory()->getTTL() * 60,
        ]);
    }

    public function guard()
    {
        return auth()->guard();
    }
}
