<?php

namespace App\Http\Controllers;

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
    private string $table;
    private string $role = 'admin';

    public function __construct()
    {
        $this->model = User::query();
        $this->table = (new User())->getTable();

        View::share('title', ucfirst('Quản lý người dùng'));
        View::share('table', $this->table);
        View::share('role', $this->role);
    }

    public function index()
    {
        try {
            $data = User::query()->get();
            $arr = [];
            foreach ($data as $each) {
                $arr[] = [
                    'id' => $each->id,
                    'username' => $each->username,
                    'message' => "Hello [" . $each->username . "]",
                ];
            }
            return $this->successResponse($arr);
        } catch (Throwable $e) {
            return $this->errorResponse($e);
        }
    }

    public function update(Request $request, $userId): JsonResponse
    {
//        dd($request->all());
        DB::beginTransaction();
        try {
            $user = $this->model->find($userId);
//            dd($request->username);
            $user->username = $request->username;
            $user->save();
            DB::commit();
            return $this->successResponse();
        } catch (Throwable $e) {
            DB::rollBack();
            return $this->errorResponse($e);
        }
    }
}
