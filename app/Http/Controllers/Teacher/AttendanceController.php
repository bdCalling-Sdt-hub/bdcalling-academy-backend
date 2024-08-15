<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Batch;
use App\Models\BatchTeacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class AttendanceController extends Controller
{

    public function index(Request $request)
    {
        $query = Attendance::with('student.user','batch');

        if ($request->filled('phone_number')) {
            $query->whereHas('student', function($q) use ($request) {
                $q->where('phone_number',$request->input('phone_number'));
            });
        }
        if ($request->filled('date')) {
            $query->where('date',$request->input('date'));
        }
        if ($request->filled('batch_id')) {
            $query->whereHas('batch', function($q) use ($request) {
                $q->where('batch_id',$request->input('batch_id'));
            });
        }

        if ($request->filled('auth_user')) {
            $teacher_id = auth()->user()->teacher->id;
            $query->whereHas('batch.teachers', function ($q) use ($teacher_id) {
                $q->where('teacher_id', $teacher_id);
            });
        }
        $attendance = $query->paginate(10);
        return response()->json($attendance);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'batch_id' => 'required|integer|exists:batches,id',
            'date' => 'required|date',
            'attendance_by' => 'required|integer',
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|integer|exists:students,id',
            'attendances.*.is_present' => 'required|boolean',
        ]);

        $batch = Batch::findOrFail($validatedData['batch_id']);

        foreach ($validatedData['attendances'] as $attendanceData) {
            Attendance::updateOrCreate(
                [
                    'batch_id' => $validatedData['batch_id'],
                    'student_id' => $attendanceData['student_id'],
                    'date' => $validatedData['date']
                ],
                [
                    'is_present' => $attendanceData['is_present'],
                    'attendance_by' => $validatedData['attendance_by']
                ]
            );
        }
        Log::info('This is some useful information.');
        return response()->json(['message' => 'Attendance recorded successfully']);
    }

    public function show(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
