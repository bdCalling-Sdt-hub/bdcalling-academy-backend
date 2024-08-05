<?php

namespace App\Http\Requests;

use App\Rules\UniqueDateTime;
use Illuminate\Foundation\Http\FormRequest;

class RoutineRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'batch_id' => 'required',
            'course_module_id' => 'required',
            'course_id' => 'required',
            'date' => ['required', 'date', 'date_format:Y-m-d'],
//            'time' => ['required', 'date_format:H:i:s', new UniqueDateTime($this->date, $this->time)], //HH:MM:SS
        ];
    }

    public function messages()
    {
        return [
            'date.required' => 'The date field is required.',
            'date.date' => 'The date must be a valid date.',
            'date.date_format' => 'The date format must be Y-m-d.',
//            'time.required' => 'The time field is required.',
//            'time.date_format' => 'The time format must be H:i:s.',
        ];
    }
}
