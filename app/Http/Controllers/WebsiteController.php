<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use App\Models\Course;
use App\Models\CourseCategory;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function filterCourse(Request $request)
    {
        $query = Batch::with('course.course_category', 'course.course_module.videos', 'teachers');

        if ($request->filled('course_category_id')) {
            $query->whereHas('course.course_category', function ($q) use ($request) {
                $q->where('id', $request->course_category_id);
            });
        }

        if ($request->filled('course_type')) {
            $query->whereHas('course', function ($q) use ($request) {
                $q->where('course_type', $request->course_type);
            });
        }

        //global search
        if ($request->filled('course_name')) {
            $query->whereHas('course', function ($q) use ($request) {
                $q->where('course_name', 'like', '%' . $request->course_name . '%');
            });
        }

        if ($request->filled('category_name')) {
            $query->whereHas('course.course_category', function ($q) use ($request) {
                $q->where('category_name', 'like', '%' . $request->category_name . '%');
            });
        }

        $filter_course = $query->paginate(9);

        return response()->json(['data' => $filter_course]);
    }

}
