<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Quize;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function ModuleWiseQuizUpdate(Request $request)
    {
        $module_id = $request->module_id;
        $quizes = Quize::where('course_module_id', $module_id)->first();
        if (empty($quizes))
        {
            return response()->json(['message' => 'Module not found'], 404);
        }
        $quizes->questions = $request->questions ?? $quizes->questions;
        $quizes->exam_name = $request->exam_name ?? $quizes->exam_name;
        $quizes->save();
        return response()->json(['message' => 'Quiz updated successfully'], 200);
    }
}
