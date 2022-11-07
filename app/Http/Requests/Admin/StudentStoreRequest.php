<?php

namespace App\Http\Requests\Admin;

use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'username'         => [
                'required',
                'string',
                Rule::unique(Student::class, 'username'),
            ],
            'firstname'        => [
                'required',
                'string',
            ],
            'lastname'         => [
                'required',
                'string',
            ],
            'gender'           => [
                'required',
                'boolean',
            ],
            'phone'            => [
                'required',
                'numeric',
                'min:8',
                Rule::unique(Student::class, 'phone'),
            ],
            'address'          => [
                'required',
                'string',
            ],
            'identification'   => [
                'required',
                'string',
            ],
            'email'            => [
                'required',
                'email',
                Rule::unique(Student::class, 'email'),
            ],
            'password'         => [
                'required',
                'min:6',
            ],
            'confirm_password' => [
                'required',
                'min:6',
                'same:password',
            ],
        ];
    }
}
