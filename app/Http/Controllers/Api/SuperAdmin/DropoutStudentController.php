<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AddStudent;
class DropoutStudentController extends Controller
{
    public function show_dropout_student(Request $request)
    {
          // Initialize the query builder for the AddStudent model and filter by status
          $query = AddStudent::where('status', 'Dropout')
          ->with(['user', 'batch', 'course']);

        // Apply filters conditionally
        if ($request->filled('date')) {
        $query->where('dob', 'like', "%{$request->date}%");
        }

        // Using where and orWhere properly
        if ($request->filled('id')) {
        $query->where(function($q) use ($request) {
            $q->where('id', 'like', "%{$request->id}%"); 
        });
        }

        if ($request->filled('batch_no')) {
        $query->where(function($q) use ($request) {
            $q->where('batch_id', 'like', "%{$request->batch_no}%");
        });
        }

        if ($request->filled('phone')) {
        $query->where(function($q) use ($request) {
            $q->where('phone', 'like', "%{$request->phone}%");
        });
        }

        if ($request->filled('course_id')) {
        $query->where(function($q) use ($request) {
            $q->where('course_id', 'like', "%{$request->course_id}%");
        });
        }

        // Paginate the results
        $students = $query->paginate(10);

        // Return the paginated results
        return response()->json($students);
    }
}