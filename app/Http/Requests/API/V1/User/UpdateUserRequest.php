<?php

namespace App\Http\Requests\API\V1\User;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|unique:users,email,' . $this->user()->id,
            'password' => 'nullable|string|min:6|confirmed',
            'username' => 'string|max:255|unique:users,username,' . $this->user()->id,
        ];
    }
}
