<?php

namespace App\Http\Controllers;

use App\Models\BatchStudent;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    public function showCertificate()
    {
        $student_id = auth()->user()->student->id;
        $completed_students = BatchStudent::with('batch.course','student.user')->where('student_id',$student_id)->where('status', 'completed')->get();
        return response()->json($completed_students);
    }
}
