<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class LeaveRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'leave_type' => 'required|string',
            'date_from' => 'required|date',
            'date_to' => 'required|date',
            'phone_number' => 'required|string',
            'reason' => 'required|string',
        ];
    }
}
