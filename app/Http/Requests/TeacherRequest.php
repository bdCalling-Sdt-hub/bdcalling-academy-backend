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
            'first_name' => 'nullable',
            'email' => 'nullable',
            'phone_number' => 'nullable',
            'designation' => 'nullable',
            'created_by' => 'nullable',
            'status' => 'nullable',
        ];
    }
}
