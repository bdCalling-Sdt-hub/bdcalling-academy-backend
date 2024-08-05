<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeacherRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string',
            'email' => 'email|unique:users,email',
            'password' => 'string|min:6|confirmed', // Assuming password is also required
            'course_category_id' => 'integer',
            'phone_number' => 'string',
            'designation' => 'string',
            'expert' => 'string',
            'role' => 'UPPERCASE|string',
            'payment' => 'string',
            'payment_method' => 'string',
            'payment_type' => 'string',
        ];
    }
}
