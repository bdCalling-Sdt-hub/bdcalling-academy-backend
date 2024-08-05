<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;
use App\Models\AddStudent;
use App\Models\Refund;
use App\Http\Requests\RefundRequest;
class DropoutStudentController extends Controller
{
    public function show_dropout_student(Request $request)
    {
          // Initialize the query builder for the AddStudent model and filter by status
          $query = Student::where('status', 'Dropout')
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

    public function store_refund(RefundRequest $request)
    {
        // Validate the request data using the rules defined in RefundRequest
        $validatedData = $request->validated();

        // Create a new refund record in the database
        $refund = new Refund();
        $refund->user_id = $validatedData['user_id'];
        $refund->course_id = $validatedData['course_id'];
        $refund->batch_id = $validatedData['batch_id'];
        $refund->refund_amount = $validatedData['refund_amount'];
        $refund->refund_by = $validatedData['refund_by'];

        // Save the record to the database
        $refund->save();

        // Optionally, you can return a response or redirect
        return response()->json(['message' => 'Refund record created successfully', 'refund' => $refund], 201);
    }
}
