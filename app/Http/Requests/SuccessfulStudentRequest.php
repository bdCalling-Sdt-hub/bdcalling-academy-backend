<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SuccessfulStudentRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_ids' => 'required',
//            'student_ids.*' => 'integer|exists:students,id',
            'batch_id' => 'required|integer|exists:batches,id',
        ];
    }
}
