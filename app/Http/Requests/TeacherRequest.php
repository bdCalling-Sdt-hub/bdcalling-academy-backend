<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TeacherRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            //
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed', // Assuming password is also required
            'course_category_id' => 'required|integer',
            'phone_number' => 'required|string',
            'designation' => 'required|string',
            'expert' => 'required|string',
            'role' => 'UPPERCASE|string',
            'payment' => 'string',
            'payment_method' => 'string',
            'payment_type' => 'string',
        ];

    }


}
