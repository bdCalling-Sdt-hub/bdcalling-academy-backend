<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModuleRequest;
use App\Models\Course;
use App\Models\CourseModule;
use Illuminate\Http\Request;

class ModuleController extends Controller
{
    //

    public function addModule(ModuleRequest $request)
    {
        $course = CourseModule::where("course_id", $request->course_id)
            ->get();

        $courseModuleTitle = CourseModule::where("course_id", $request->course_id)
            ->where("module_title", strtolower($request->module_title))
            ->get();

        $result = CourseModule::create([
            "course_id" => $request->course_id,
            "module_title" => strtolower($request->module_title),
            "created_by" => $request->created_by,
            "module_no" => (string)count($course)+1,
            "module_class" => json_encode($request->module_class),
        ]);
//        $course=Course::find($request->course_id);
//        $course->publish=1;
//        $course->update();

        return response()->json([
            'status' => 200,
            "message" => "Module added successfully",
            'data' => $result,
        ]);
    }
    public function showModule()
    {
        $course_list_with_module = Course::with('course_module')->get();

        $formatted_course_list = $course_list_with_module->map(function ($course){
            foreach ($course->course_module as $module) {
                $module->module_class = json_decode($module->module_class);
            }
            return $course;
        });

        return dataResponse(200, 'Course With Module List', $formatted_course_list);
    }

}
