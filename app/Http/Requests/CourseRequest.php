<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CourseRequest extends FormRequest
{

    public function authorize(): bool
    {
        return true;
    }


    public function rules(): array
    {
        return [
            'course_category_id' => 'required',
            'course_name' => 'required|string|min:2|max:100',
            'language' => 'required',
            'course_details' => 'required',
            'course_time_length' => 'required',
            'price' => 'required',
            'max_student_length' => 'nullable',
            'skill_Level' => 'required',
            'address' => 'nullable',
            'image' => 'required|mimes:jpg,png,jpeg,gif,svg|max:2048',
            'career_opportunities' => 'required',
            'curriculum' => 'required',
            'tools' => 'required',
            'job_position' => 'required',
            'popular_section' => 'required',
            'course_type' => 'required',
        ];
    }
}
