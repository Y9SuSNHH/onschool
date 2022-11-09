<?php

namespace App\Http\Controllers;

use App\Http\Requests\Admin\StudentStoreRequest;
use App\Http\Requests\Admin\StudentUpdateRequest;
use App\Models\Student;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Throwable;

class StudentController extends Controller
{
    use ResponseTrait;

    private object $model;

    public function __construct()
    {
        $this->model = Student::query();
    }

    public function list(): JsonResponse
    {
        try {
            $query = $this->model->clone()
                ->with('created_by:id,username,role')
                ->latest()->paginate();

            $data['data']       = $query->getCollection();
            $data['pagination'] = $query->linkCollection();
            return $this->successResponse($data);
        } catch (Throwable $e) {
            Log::warning($e->getMessage());
            return $this->errorResponse($e);
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

    public function store(StudentStoreRequest $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $password = Hash::make($request->password);

            $data = $request->validated();
            $data = Arr::set($data, 'password', $password);
            $data = Arr::add($data, 'created_by', auth()->user()->id);
            $this->model->create(Arr::except($data, 'confirm_password'));
            DB::commit();
            Log::info('Successfully created student.');
            return $this->successResponse([], 'Create student');
        } catch (Throwable $e) {
            DB::rollBack();
            Log::warning($e->getMessage());
            return $this->errorResponse($e->getMessage());
        }
    }

    public function update(StudentUpdateRequest $request, $userId): JsonResponse
    {
        DB::beginTransaction();
        try {
            $user = $this->model->find($userId);
            $user->fill($request->validated());
            $user->updated_by = auth()->user()->id;
            $user->save();
            DB::commit();
            Log::info('Successfully updated student.');
            return $this->successResponse([], 'Edit student');
        } catch (Throwable $e) {
            DB::rollBack();
            Log::warning($e->getMessage());
            return $this->errorResponse($e);
        }
    }

    public function destroy($id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $student = $this->model->where('id', $id)->firstOrFail();

            $student->deleted_by = auth()->user()->id;
            $student->delete();
            DB::commit();
            Log::info('Successfully add student to trash');
            return $this->successResponse([], 'Delete student');
        } catch (Throwable $e) {
            DB::rollBack();
            Log::warning($e->getMessage());
            return $this->errorResponse($e->getMessage());
        }
    }
}
