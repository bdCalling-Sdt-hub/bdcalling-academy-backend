<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PhoenixBatchRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'batch_name' => 'nullable | string ',
            'course_id' => 'required',
            'start_date' => 'required',
            'end_date' => 'required',
            'seat_limit' => 'nullable',
            'seat_left' => 'nullable',
            'image' => 'required',
            'cost' => 'nullable',
            'cost_status' => 'nullable | string ',
        ];
    }
}
