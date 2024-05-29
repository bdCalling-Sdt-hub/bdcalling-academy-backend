<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModuleRequest;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ModuleController extends Controller
{
    //
    public function addModule(ModuleRequest $request)
    {
        DB::beginTransaction();
        try {

            $courseModuleTitle = CourseModule::where("course_id", $request->course_id)
                ->where("module_title", strtolower($request->module_title))
                ->first();

            if ($courseModuleTitle) {
                return response()->json([
                    'status' => 400,
                    'message' => 'Module title already exists for this course',
                ], 400);
            }

            // Create the module
            $courseModules = CourseModule::where("course_id", $request->course_id)->get();
            $module = CourseModule::create([
                "course_id" => $request->course_id,
                "module_title" => strtolower($request->module_title),
                "created_by" => $request->created_by,
                "module_no" => (string)($courseModules->count() + 1),
                "module_class" => $request->module_class,
            ]);

            $videos = json_decode($request->module_class, true);

            foreach ($videos as $index => $video) {
                return Video::create([
                    'course_module_id' => $module->id,
                    'name' => $video['name'],
                    'video_url' => $video['video'],
                    'order' => $index + 1,
                ]);
            }

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Module added successfully',
                'data' => $module->load('videos'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error adding module: ' . $e->getMessage());

            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while adding the module',
            ], 500);
        }
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
