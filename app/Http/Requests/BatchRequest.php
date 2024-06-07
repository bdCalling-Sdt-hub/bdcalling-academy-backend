<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BatchRequest extends FormRequest
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
            'seat_limit' => 'required',
            'seat_left' => 'required',
            'image' => 'required',
            'discount_price' => 'nullable',
        ];
    }
}
