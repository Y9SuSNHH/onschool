<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleEnum;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    use ResponseTrait;

    public function __construct()
    {
        $this->middleware('jwt.auth', ['except' => ['login']]);
    }

    public function profile(): JsonResponse
    {
        return $this->successResponse(auth()->user());
    }

    /**
     * @throws ValidationException
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'username' => 'required|string',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        $token = auth()->attempt($validator->validated());
        if (!$token) {
            return $this->errorResponse('Unauthorized', 401);
        }
        return $this->respondWithToken($token);
    }

    public function logout(): JsonResponse
    {
        auth()->logout();

        return $this->successResponse([], 'Successfully logged out');
    }

    protected function respondWithToken($token): JsonResponse
    {
        return $this->successResponse([
            'access_token' => $token,
            'token_type'   => 'bearer',
            'expires_in'   => auth()->factory()->getTTL() * 60,
            'role'         => strtolower(UserRoleEnum::getKey(auth()->user()->role)),
        ]);
    }

}
