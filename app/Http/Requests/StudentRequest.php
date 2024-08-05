<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'unique:students,phone_number',
            'gender' =>  'in:male,female',
            'religion' =>'string',
            'dob' => 'nullable',
            'blood_group',
            'address',
            'add_by',
            'student_type',
        ];
    }
}
