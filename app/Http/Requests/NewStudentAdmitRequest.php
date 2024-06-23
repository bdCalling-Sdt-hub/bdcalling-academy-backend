<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewStudentAdmitRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'category_id' => 'required|integer|exists:course_categories,id',
            'phone_number' => 'required|string|max:15',
            'gender' => 'required|string|max:10',
            'religion' => 'nullable|string|max:50',
            'registration_date' => 'required|date',
            'dob' => 'required|date',
            'blood_group' => 'nullable|string|max:3',
            'address' => 'required|string|max:255',
            'add_by' => 'required|string',
            'student_type' => 'required|string|max:50',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'batch_id' => 'required|integer|exists:batches,id',
        ];
    }
}
