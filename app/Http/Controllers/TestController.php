<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Illuminate\Http\Request;

class TestController extends Controller
{
    public function createBatch()
    {
        $course_id = 1;
        $course_count = Course::where('id', $course_id)->count();
        $count_formatted = str_pad($course_count, 2, '0', STR_PAD_LEFT);
        $academy_name = 'BAC';
        $course_type = 'Online';
        $course_name = 'php and laravel';
        $course_type = strtoupper($course_type);
        $course_type_filter = ($course_type === 'ONLINE') ? 'O' : '';

        $course_name_filter = strtoupper(substr($course_name, 0, 3));

        $year = date('y');

        $batch_id = $academy_name . '-' . $course_type_filter . $course_name_filter . '-' . $year . $count_formatted;

        return response()->json([
            'message' => 'Batch is created successfully',
            'data' => $batch_id,
        ]);
    }
}
