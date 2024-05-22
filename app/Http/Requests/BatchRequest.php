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
            //
            'batch_name' => 'required | string ',
            'course_id' => 'required',
            'start_time' => '',
            'end_time' => '',
            'total_seat' => 'string',
            'seat_left' => 'nullable',
            'discount' => 'float'

        ];
    }
}
