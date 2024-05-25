<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class OrderRequest extends FormRequest
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
            'user_id'=>'required',
            'add_student_id'=>'required',
            'batch_id'=>'required',
            'course_id'=>'required',
            'gateway_name'=>'required',
            'amount'=>'required',
            'transaction_id'=>'required',
            'currency'=>'required',
 
            
        ];
    }
}
