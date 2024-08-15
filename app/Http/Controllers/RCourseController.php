<?php

namespace App\Http\Controllers;

use App\Http\Requests\CourseRequest;
use App\Models\Course;
use Illuminate\Http\Request;

class RCourseController extends Controller
{
    public function index(Request $request)
    {
        //
        $query = Course::query();

        if ($request->filled('course_type')) {
                $query->where('course_type', $request->input('course_type'));
        }
        if ($request->filled('no_pagination')) {
            $courses = $query->get();
            return response($courses, 200);
        }
        $courses = $query->paginate(8);
        return response($courses, 200);
    }

    public function create()
    {
        //
    }

    public function store(CourseRequest $request)
    {
        try {
            $courses = new Course();
            $courses->course_category_id = $request->course_category_id;
            $courses->course_name = $request->course_name;
            $courses->language = $request->language;
            $courses->course_details = $request->course_details;
            $courses->course_time_length = $request->course_time_length;
            $courses->price = $request->price;
            $courses->max_student_length = $request->max_student_length;
            $courses->skill_Level = $request->skill_Level;
            $courses->address = $request->address;
            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $courses->thumbnail = saveImage($request, 'image');
            }
            $courses->career_opportunities = $request->career_opportunities;
            $courses->curriculum = $request->curriculum;
            $courses->tools = $request->tools;
            $courses->job_position = $request->job_position;
            $courses->popular_section = $request->popular_section;
            $courses->status = 'pending';
            $courses->course_type = $request->course_type;
            $courses->save();

            return response()->json([
                'status' => 200,
                'message' => 'course added successfully',
                'data' => $courses,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error adding course: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function show(string $id)
    {
        //
        $course = Course::where('id',$id)->first();
        if(empty($course)){
            return response()->json([
                'status' => 404,
                'message' => 'Course Does not exist',
            ]);
        }
        return dataResponse(200,'Course',$course);
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        try {
            $course = Course::findOrFail($id);
            $course->course_category_id = $request->course_category_id ?? $course->course_category_id;
            $course->course_name = $request->course_name ?? $course->course_name;
            $course->language = $request->language ?? $course->language;
            $course->course_details = $request->course_details ?? $course->course_details;
            $course->course_time_length = $request->course_time_length ?? $course->course_time_length;
            $course->price = $request->price ?? $course->price;
            $course->max_student_length = $request->max_student_length ?? $course->max_student_length;
            $course->skill_Level = $request->skill_Level ?? $course->skill_Level;
            $course->address = $request->address ?? $course->address;
            $course->career_opportunities = $request->career_opportunities ?? $course->career_opportunities;
            $course->curriculum = $request->curriculum ?? $course->curriculum;
            $course->tools = $request->tools ?? $course->tools;
            $course->job_position = $request->job_position ?? $course->job_position;
            $course->popular_section = $request->popular_section ?? $course->popular_section;
            $course->status = $request->status ?? $course->status; // You may want to update the status as well
            $course->course_type = $request->course_type ?? $course->course_type;

            if ($request->hasFile('image') && $request->file('image')->isValid()) {
                $course->thumbnail = saveImage($request, 'image');
            }

            $course->save();
            return response()->json([
                'status' => 200,
                'message' => 'Course updated successfully',
                'data' => $course,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => 'Error updating course: ' . $e->getMessage(),
            ], 500);
        }

    }

    public function destroy(string $id)
    {
        // Find the product by id
        $course = Course::where('id', $id)->first();

        if (!$course) {
            return response()->json(['message' => 'Course does not exist'],404);
        }
        $course->delete();
        return response()->json([
            'status' => 200,
            'message' => 'Course Delete Successfully'
        ]);
    }
}
