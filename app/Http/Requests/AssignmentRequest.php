<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AssignmentRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'module_id' => 'required',
            'batch_id' => 'required',
            'time' => 'required',
            'date' => 'required',
            'assignment_name' => 'required',
            'question_link' => 'required|url',
        ];
    }
}
