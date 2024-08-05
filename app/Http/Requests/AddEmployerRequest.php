<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AddEmployerRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|min:2',
            'email' => 'required|email|max:100|unique:users,email',
            'image' => 'nullable',
            'password' => 'required|string|min:6|confirmed',
            'role' => ['required', 'string', Rule::in(['ADMIN', 'SUPER ADMIN'])],
        ];
    }
}
