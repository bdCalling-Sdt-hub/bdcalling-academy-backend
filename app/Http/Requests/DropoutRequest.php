<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class DropoutRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'student_id' => 'required',
            'batch_id' => 'required',
        ];
    }
}
