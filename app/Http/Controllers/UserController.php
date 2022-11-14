<?php

namespace App\Http\Controllers;

use App\Enums\UserActiveEnum;
use App\Enums\UserRoleEnum;
use App\Http\Requests\Admin\UserStoreRequest;
use App\Http\Requests\Admin\UserUpdateRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Throwable;

class UserController extends Controller
{
    use ResponseTrait;

    private object $model;
    private object $users;

    public function __construct()
    {
        $this->users = new User();
        $this->model = User::query();
    }

    public function list(Request $request): JsonResponse

    {
        try {
            $table_length = 15;
            if ($request->has('table_length')) {
                $table_length = $request->get('table_length');
            }

            $filter = $this->users->handleFilter($request);
            $list   = $this->users->list($filter)->paginate($table_length);

            $data['data']      = $list->getCollection();
            $data['total']     = $list->total();
            $data['last_page'] = $list->lastPage();
            $data['per_page']  = $list->perPage();

            return $this->successResponse($data);
        } catch (Throwable $e) {
            Log::warning($e->getMessage());
            return $this->errorResponse($e->getMessage());
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
            Log::info('Successfully created user.');
            return $this->successResponse([], 'Create user');
        } catch (Throwable $e) {
            DB::rollBack();
            Log::warning($e->getMessage());
            return $this->errorResponse($e->getMessage());
        }
    }

    public function show($id): JsonResponse
    {
        try {
            $data = $this->model->find($id);
            return $this->successResponse($data);
        } catch (Throwable $e) {
            Log::warning($e->getMessage());
            return $this->errorResponse($e->getMessage());
        }
    }

    public function update(UserUpdateRequest $request, $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $key = $this->users->validateRequired($request->all());
            if ($key !== true) {
                return $this->errorResponse('The ' . $key . ' field is required.');
            }
            $user = $this->model->find($id);
            $user->fill($request->validated());
            $user->updated_by = auth()->user()->id;
            $user->save();
            DB::commit();
            Log::info('Successfully updated user');
            return $this->successResponse([], 'Edit user');
        } catch (Throwable $e) {
            DB::rollBack();
            Log::warning($e->getMessage());
            return $this->errorResponse($e->getMessage());
        }
    }

    public function destroy($id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $user = $this->model->find($id);
            if (!$user) {
                return $this->errorResponse('This user does not exist');
            }
            $user->deleted_by = auth()->user()->id;
            $user->save();
            $user->delete();
            DB::commit();
            Log::info('Successfully add user to trash');
            return $this->successResponse([], 'Delete user');
        } catch (Throwable $e) {
            DB::rollBack();
            Log::warning($e->getMessage());
            return $this->errorResponse($e->getMessage());
        }
    }
}
