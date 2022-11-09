<?php

namespace App\Http\Controllers;

use App\Enums\UserRoleEnum;
use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\View;
use Throwable;
use function PHPUnit\Framework\isNull;

class UserController extends Controller
{
    use ResponseTrait;

    private object $model;

    public function __construct()
    {
        $this->model = User::query();
    }

    public function list(Request $request): JsonResponse
    {
        try {
            $query = $this->model->clone();
            if (!empty($request->deleted_type)) {
                if ($request->deleted_type === 'All') {

                    $query->withTrashed();
                }
                if ($request->deleted_type === '1') {
                    $query->onlyTrashed();
                }
            }
            if (!is_null($request->active)) {
                if ($request->active === '1') {
                    $query->where('active', '=', 1);
                }
                if ($request->active === '0') {
                    $query->where('active', '=', 0);
                }
            }
            if (auth()->user()->role === UserRoleEnum::USER) {
                $query->where('role', UserRoleEnum::USER);
            }
            if (!empty($request->username)) {
                $query->where('username', $request->username);
            }
            $collection         = $query->latest()->paginate();
            $data['data']       = $collection->getCollection();
            $data['pagination'] = $collection->linkCollection();
            return $this->successResponse($data);
        } catch (Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    public function store(UserStoreRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $password = Hash::make($request->password);
            $this->model->create([
                'username'   => $request->username,
                'firstname'  => $request->firstname,
                'lastname'   => $request->lastname,
                'phone'      => $request->phone,
                'gender'     => $request->gender,
                'email'      => $request->email,
                'password'   => $password,
                'role'       => $request->role,
                'created_by' => auth()->user()->id,
            ]);
            DB::commit();
            return $this->successResponse([], 'Create user');
        } catch (Throwable $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }

    public function updateActive($id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $user         = $this->model->find($id);
            $user->active = !$user->active;
            $user->save();
            DB::commit();
            return $this->successResponse([], 'Updated active');
        } catch (Throwable $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }

    public function show($id): JsonResponse
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
            $user->updated_by = auth()->user()->id;
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
            $user             = $this->model->where('id', $id)->firstOrFail();
            $user->deleted_by = auth()->user()->id;
            $user->delete();
            DB::commit();
            return $this->successResponse([], 'Delete user');
        } catch (Throwable $e) {
            DB::rollBack();
            return $this->errorResponse($e->getMessage());
        }
    }
}
