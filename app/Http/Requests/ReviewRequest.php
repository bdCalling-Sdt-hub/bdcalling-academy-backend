<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ReviewRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'student_id'=>'exists:students,id',
            'course_id'=>'required|exists:courses,id',
            'batch_id'=>'required|exists:batches,id',
            'rating_value'=>'required',
            'message'=>'required',
        ];
    }
}
