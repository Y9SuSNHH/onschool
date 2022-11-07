<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Throwable;

class JwtController extends Controller
{
    use ResponseTrait;

//    public function __construct()
//    {
//        $this->middleware('jwt.auth', ['except' => ['login']]);
//    }

    public function profile(): JsonResponse
    {
        return $this->successResponse(auth()->user());
    }

    public function payload(): JsonResponse
    {
        return $this->successResponse(auth()->payload());
    }

    /**
     * @throws ValidationException
     */
    public function login(Request $request): JsonResponse
    {
        try {
            $validator = Validator::make($request->all(), [
                'username' => 'required|string',
                'password' => 'required|string|min:6',
            ]);
            if ($validator->fails()) {
                return $this->errorResponse($validator->errors(), 422);
            }
            $data = $validator->validated();
            $user = User::query()->where('username', $data['username'])->firstOrFail();
            if (!Hash::check($data['password'], $user->password)) {
                return $this->errorResponse('Incorrect password');
            }
            $payload = [
//                'username' => $user->username,
                'role' => $user->role,
            ];
            $token   = auth()->claims($payload)->login($user);
            $token   = auth()->login($user);
            if (!$token) {
                return $this->errorResponse('Unauthorized', 401);
            }
            return $this->respondWithToken($token);
        } catch (Throwable $e) {
            return $this->errorResponse($e->getMessage());
        }
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
        ]);
    }

}
