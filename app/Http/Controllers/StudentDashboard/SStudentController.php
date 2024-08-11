<?php

namespace App\Http\Controllers\StudentDashboard;

use App\Http\Controllers\Controller;
use App\Models\BatchStudent;
use App\Models\Student;
use Illuminate\Http\Request;

class SStudentController extends Controller
{
    //

    public function enrolledCourses(Request $request)
    {
        $student_id = auth()->user()->student->id;
        $query = BatchStudent::with('batch.course.course_module.videos','batch.teachers')->where('student_id',$student_id);
        if($request->filled('id')){
            $query->where('id',$request->id);
        }
        $enrolledCourses = $query->get();
        return response()->json($enrolledCourses);
    }

//    public function enrolledCourses(Request $request)
//    {
//        $student_id = auth()->user()->student->id;
//
//        // Eager load only the necessary relationships
//        $enrolledCourses = BatchStudent::with(['batch.course.course_module.videos'])
//            ->where('student_id', $student_id)
//            ->get()
//            ->map(function ($batchStudent) {
//                return [
//                    'course_modules' => $batchStudent->batch->course->course_module->map(function ($module) {
//                        return [
//                            'module_title' => $module->module_title,
//                            'videos' => $module->videos->map(function ($video) {
//                                return [
//                                    'name' => $video->name,
//                                    'video_url' => $video->video_url,
//                                    'order' => $video->order
//                                ];
//                            })
//                        ];
//                    })
//                ];
//            });
//
//        return response()->json($enrolledCourses, 200);
//    }


}
