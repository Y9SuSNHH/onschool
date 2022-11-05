<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleEnum;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use Throwable;

class UserController extends Controller
{
    use ResponseTrait;

    private object $model;
//    private string $table;
//    private string $role = 'admin';

    public function __construct()
    {
        $this->model = User::query();
//        $this->table = (new User())->getTable();
//
//        View::share('title', ucfirst('Quản lý người dùng'));
//        View::share('table', $this->table);
//        View::share('role', $this->role);
    }

    public function list(): JsonResponse
    {
        try {
            $query = $this->model->clone()->latest()->paginate();

            $data['data']       = $query->getCollection();
            $data['pagination'] = $query->linkCollection();
            return $this->successResponse($data);
        } catch (Throwable $e) {
            return $this->errorResponse($e);
        }
    }

//    public function store(UserUpdateRequest $request): JsonResponse
//    {
//        try {
//        } catch (Throwable $e) {
//
//        }
//    }

    public function updateActive($id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $user         = $this->model->find($id);
            $user->active = !$user->active;
            $user->save();
            DB::commit();
            return $this->successResponse([], 'Successfully updated active');
        } catch (Throwable $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }

    public function profile($id): JsonResponse
    {
        try {
            $data = $this->model->find($id);
            return $this->successResponse($data);
        } catch (Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    public function update(UserUpdateRequest $request, $userId): JsonResponse
    {
        DB::beginTransaction();
        try {
            $user = $this->model->find($userId);
            $user->fill($request->validated());
            $user->save();
            DB::commit();
            return $this->successResponse([], 'Edit user');
        } catch (Throwable $e) {
            DB::rollBack();
            return $this->errorResponse($e);
        }
    }

    public function destroy($id): JsonResponse
    {
        DB::beginTransaction();
        try {
            User::destroy($id);
            DB::commit();
            return $this->successResponse([], 'Delete user');
        } catch (Throwable $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }
}
