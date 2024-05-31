<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class QuizeRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'course_module_id'=>'required',
            'questions'=>'required',
            'currect_ans'=>'required',
            'opt_1'=>'required',
            'opt_2'=>'required',
            'opt_3'=>'required',
            'opt_4'=>'required',
            'mark'=>'required',
        ];
    }
}
