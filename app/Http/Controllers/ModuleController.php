<?php

namespace App\Http\Controllers;

use App\Http\Requests\ModuleRequest;
use App\Models\BatchTeacher;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\Quize;
use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\NotificationController;

class ModuleController extends Controller
{
    public function addModule(ModuleRequest $request)
    {
        DB::beginTransaction();
        try {
            // Check if the module title already exists for the course
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
//                "module_class" => $request->module_class,
            ]);

            // Decode the module_class JSON
            $videos = json_decode($request->module_class, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON in module_class');
            }

            // Ensure videos is an array
            if (!is_array($videos)) {
                throw new \Exception('module_class should be an array of videos');
            }

            // Create each video
            foreach ($videos as $index => $video) {
                $videoObj = new Video;
                $videoObj->course_module_id = $module->id;
                $videoObj->name = $video['name'];
                $videoObj->video_url = $video['video'];
                $videoObj->order = $index + 1;
                $videoObj->save();
            }

            $quizes = json_decode($request->questions, true);


            if ($request->questions) {
                foreach ($quizes as $index => $quiz) {
                    $quiz = new Quize();
                    $quiz->course_module_id = $module->id;
                    $quiz->questions = $request->questions;
                    $quiz->exam_name = $request->exam_name;
                    $quiz->marks = $request->marks;
                    $quiz->save();
                }
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

    public function updateModule(Request $request, $moduleId)
    {
        DB::beginTransaction();
        try {
            // Find the module
            $module = CourseModule::findOrFail($moduleId);

            $module->module_title = strtolower($request->module_title);
            $module->module_no = $request->module_no ?? $module->module_no;
            $module->created_by = $request->created_by ?? $module->created_by;
//            $module->module_class = $request->module_class ?? $module->module_class;
            $module->save();

            $videos = json_decode($request->module_class, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON in module_class');
            }

            if (!is_array($videos)) {
                throw new \Exception('module_class should be an array of videos');
            }

            foreach ($videos as $videoData) {
                $video = Video::findOrFail($videoData['id']);
                $video->name = $videoData['name'] ?? $video->name;
                $video->video_url = $videoData['video_url'] ?? $video->video_url;
                $video->order = $videoData['order'] ?? $video->order;
                $video->save();
            }

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Module and videos updated successfully',
                'data' => $module->load('videos'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating module: ' . $e->getMessage());

            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while updating the module',
            ], 500);
        }
    }

    public function updateModuleVideo(Request $request, $moduleId)
    {
        DB::beginTransaction();
        try {
            // Find the module
            $module = CourseModule::findOrFail($moduleId);

            // Update module fields
            $module->module_title = strtolower($request->module_title);
            $module->module_no = $request->module_no ?? $module->module_no;
            $module->created_by = $request->created_by ?? $module->created_by;
            $module->save();

            // Decode module_class JSON
            $videos = json_decode($request->module_class, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new \Exception('Invalid JSON in module_class');
            }

            if (!is_array($videos)) {
                throw new \Exception('module_class should be an array of videos');
            }

            // Delete existing videos for the module
            Video::where('course_module_id', $moduleId)->delete();

            // Add new videos
            foreach ($videos as $index => $video) {
                $videoObj = new Video;
                $videoObj->course_module_id = $module->id;
                $videoObj->name = $video['name'];
                $videoObj->video_url = $video['video'];
                $videoObj->order = $index + 1;
                $videoObj->save();
            }

            DB::commit();

            return response()->json([
                'status' => 200,
                'message' => 'Module and videos updated successfully',
                'data' => $module->load('videos'),
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error updating module: ' . $e->getMessage());

            return response()->json([
                'status' => 500,
                'message' => 'An error occurred while updating the module',
            ], 500);
        }
    }


    public function showModule(Request $request)
    {

        $course_id = $request->course_id;
        $query = Course::with('course_module.videos', 'course_module.quiz');
        if ($request->filled('course_id')) {
            $query->where('id', $course_id);
        }
        if ($request->filled('teacher_id')) {
            $teacher_id = auth()->user()->teacher->id;
            $batch_teacher = BatchTeacher::where('teacher_id', $teacher_id)->get();
            $course_ids = $batch_teacher->map(function ($item) {
                return $item->batch->course_id;
            });
            $query->whereIn('id', $course_ids);
        }
        $course_list_with_module = $query->paginate(9);
        return dataResponse(200, 'Course With Module List', $course_list_with_module);
    }

    public function getSingleModule(string $id)
    {
        $query = CourseModule::with('videos','quiz')->where('id',$id)->first();
        return dataResponse(200, 'Module Details', $query);
    }

    public function updateQuiz(Request $request, $moduleId)
    {
        try {
            // Decode the questions from JSON format
            $quizes = json_decode($request->questions, true);

            // Check if the questions data is not empty
            if (empty($quizes)) {
                return response()->json(['error' => 'No questions provided.'], 400);
            }

            // Delete previous quizzes for the given module
            Quize::where('course_module_id', $moduleId)->delete();

            // Loop through the decoded questions and create new quiz entries
            foreach ($quizes as $quiz) {
                $quiz = new Quize();
                $quiz->course_module_id = $moduleId;
                $quiz->questions = $request->questions;
                $quiz->exam_name = $request->exam_name;
                $quiz->marks = $request->marks;
                $quiz->save();
            }

            return response()->json(['success' => 'Quiz updated successfully.'], 200);

        } catch (\Exception $e) {
            // Handle any errors that may occur
            return response()->json(['error' => 'An error occurred: ' . $e->getMessage()], 500);
        }
    }

}
