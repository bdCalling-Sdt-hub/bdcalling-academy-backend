<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class EventRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'course_name' => 'required',
            'date' => 'required',
            'time' => 'required',
            'end_time' => 'required',
            'locations' => 'required',
            'descriptions' => 'required',
            'image' => 'required',
        ];
    }
}
