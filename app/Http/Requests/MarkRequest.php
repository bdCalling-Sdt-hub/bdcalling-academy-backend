<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MarkRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => 'required|exists:students,id',
            'batch_id' => 'required',
            'score' => 'required',
            'date' => 'required',
        ];
    }
}
