<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateStudentRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'string|max:255',
            'email' => 'string|email|max:255|exists:users,email',
            'password' => 'string|min:8|confirmed',
            'category_id' => 'integer|exists:course_categories,id',
            'phone_number' => 'string|max:15',
            'gender' => 'string|max:10',
            'religion' => 'nullable|string|max:50',
            'registration_date' => 'date',
            'dob' => 'date',
            'blood_group' => 'nullable|string|max:3',
            'address' => 'string|max:255',
            'add_by' => 'string',
            'student_type' => 'string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'batch_id' => 'integer|exists:batches,id',
        ];
    }
}
