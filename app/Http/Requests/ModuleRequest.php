<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class ModuleRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            //
            'course_id' => 'required',
            'module_title' => 'required|string',
            'module_class' => 'required',
            'created_by' => ''
        ];
    }
}
