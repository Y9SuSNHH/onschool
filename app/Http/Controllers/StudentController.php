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
    private object $students;

    public function __construct()
    {
        $this->students = new Student();
        $this->model    = Student::query();
    }

    public function list(Request $request): JsonResponse
    {
        try {
            $table_length = 5;
            if ($request->has('table_length')) {
                $table_length = $request->get('table_length');
            }

//            $filter = $this->students->handleFilter($request);
            $list = $this->students->list()->paginate($table_length);

            $data['data']      = $list->getCollection();
            $data['total']     = $list->total();
            $data['last_page'] = $list->lastPage();
            return $this->successResponse($data);
        } catch (Throwable $e) {
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
            $key = $this->students->validateRequired($request->all());
            if ($key !== true) {
                return $this->errorResponse('The ' . $key . ' field is required.');
            }
            $user = $this->students->checkFind($userId);
            $user->fill($request->validated());
            $user->updated_by = auth()->user()->id;
            $user->save();
            DB::commit();
            Log::info('Successfully updated student.');
            return $this->successResponse([], 'Edit student');
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
            $student = $this->students->checkFind($id);

            $student->deleted_by = auth()->user()->id;
            $student->save();
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
