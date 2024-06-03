<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\BatchStudent;
use App\Models\Student;
use Illuminate\Http\Request;

class AdmitController extends Controller
{
    public function admitStudent(Request $request)
    {
        $student_id = $request->student_id;
        $student = Student::find($student_id);
        if (empty($student))
        {
            return response()->json(['message' => 'Student Does Not Exist', 'data' => $student]);
        }
        $batch_student = new BatchStudent();
        $batch_student->batch_id = $request->batch_id;
        $batch_student->student_id = $student->id;
        $batch_student->save();
        return response()->json(['message' => 'Assign Batch To Student Successfully' , 'data' => $batch_student]);
    }
}
