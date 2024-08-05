<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuizeRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_module_id'=>'required',
            'questions'=>'required',
            'mark'=>'nullable',
            'exam_name' => 'required'
        ];
    }
}
