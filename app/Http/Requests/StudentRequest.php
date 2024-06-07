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
            'phone_number',
            'gender',
            'religion',
            'registration_date',
            'dob',
            'blood_group',
            'address',
            'add_by',
            'student_type',
        ];
    }
}
