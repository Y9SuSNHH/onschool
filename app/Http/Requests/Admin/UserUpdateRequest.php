<?php

namespace App\Http\Requests\Admin;

use App\Enums\UserRoleEnum;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UserUpdateRequest extends FormRequest
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
            'firstname' => [
                'string',
            ],
            'lastname'  => [
                'string',
            ],
            'gender'    => [
                'boolean',
            ],
            'phone'     => [
                'numeric',
                'min:8',
                Rule::unique(User::class, 'phone')->ignore($this->phone, 'phone'),
            ],
            'email'     => [
                'email',
            ],
            'active'     => [
                'boolean',
            ],
            'role'      => [
                Rule::in(UserRoleEnum::getValues()),
            ],
        ];
    }
}
