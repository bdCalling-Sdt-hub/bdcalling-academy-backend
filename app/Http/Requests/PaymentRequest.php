<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PaymentRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
                'teacher_id' => 'required',
                'course_module_id' => 'nullable',
                'payment_type' => 'required|string',
                'amount' => 'required|regex:/^\d+(\.\d{1,2})?$/',
                'payment_date' => 'required|date_format:Y-m-d',
                'reference_by' => 'nullable|string',
        ];
    }
}
