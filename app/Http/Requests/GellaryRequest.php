<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class GellaryRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'image' => 'mimes:jpeg,jpg,png,gif,webp',
        ];
    }
}
