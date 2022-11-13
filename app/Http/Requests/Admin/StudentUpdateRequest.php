<?php

namespace App\Http\Requests\Admin;

use App\Models\Student;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StudentUpdateRequest extends FormRequest
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
    public function rules()
    {
        return [
            'firstname'        => [
                'string',
            ],
            'lastname'         => [
                'string',
            ],
            'gender'           => [
                'boolean',
            ],
            'phone'            => [
                'numeric',
                'min:8',
                Rule::unique(Student::class, 'phone')->ignore($this->phone, 'phone'),
            ],
            'address'          => [
                'string',
            ],
            'identification'   => [
                'string',
            ],
            'email'            => [
                'email',
            ],
        ];
    }
}
