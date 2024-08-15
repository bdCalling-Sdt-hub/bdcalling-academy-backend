<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RefundRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'batch_id'=>'required|exists:batches,id',
            'student_id'=>'required|exists:students,id',
            'refund_amount'=>'required',
            'refund_by'=>'string',
        ];
    }
}
