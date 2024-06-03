<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BatchSyncRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
                'batch_id' => 'required',
                'teacher_user_id' => 'required', //teacher user_id
                'start_date' => 'date|required',
                'end_date' => 'date|required',
                'seat_limit' => 'integer',
                'seat_left' => 'integer',
                'discount_price' => 'nullable',
                'image' => 'required',
        ];
    }
}
