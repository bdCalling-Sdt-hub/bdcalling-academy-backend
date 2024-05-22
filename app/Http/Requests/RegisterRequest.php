<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;


class RegisterRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            //
            'name' => 'required|string|min:2|max:100',
            'email' => 'required|string|email|max:60',
            'password' => 'required|string|min:6|confirmed',
            'role' => ['required', Rule::in(['STUDENT', 'ADMIN', 'SUPER ADMIN', 'TRAINER'])],
//            'email.contains_dot' => 'without (.) Your email is invalid',
            'otp' => '',

        ];
    }
}
