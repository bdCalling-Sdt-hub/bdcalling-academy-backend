<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\MarkRequest;
use App\Models\Mark;
use Illuminate\Http\Request;

class MarkController extends Controller
{
    public function showStudentMark(Request $request)
    {
        return $student_marks = Mark::with('student.user')->paginate(9);
    }
    public function studentMark(MarkRequest $request)
    {
        $existing_mark = Mark::where('student_id', $request->student_id)
            ->where('date', $request->date)
            ->first();

        if ($existing_mark) {
            return response()->json(['message' => 'Marks cannot be assigned more than once on the same date for the same student'], 400);
        }

        $student_mark = new Mark();
        $student_mark->student_id = $request->student_id;
        $student_mark->batch_id = $request->batch_id;
        $student_mark->score = $request->score;
        $student_mark->date = $request->date;
        $student_mark->save();
        return response()->json(['message' => 'Student Mark Added Successfully','data' => $student_mark],200);
    }


    public function updateStudentMark(Request $request, $id)
    {
        $student_mark = Mark::find($id);

        if (!$student_mark) {
            return response()->json(['message' => 'Mark not found'], 404);
        }

        $existing_mark = Mark::where('student_id', $request->student_id)
            ->where('date', $request->date)
            ->where('id', '!=', $id)
            ->first();

        if ($existing_mark) {
            return response()->json(['message' => 'Marks cannot be assigned more than once on the same date for the same student'], 400);
        }

        $student_mark->score = $request->score ?? $student_mark->score;
        $student_mark->date = $request->date ?? $student_mark->date;
        $student_mark->update();

        return response()->json(['message' => 'Student Mark Updated Successfully' , 'data' => $student_mark], 200);
    }

}
