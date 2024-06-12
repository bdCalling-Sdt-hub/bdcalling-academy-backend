<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\Batch;
use Illuminate\Http\Request;

class AttendanceController extends Controller
{

    public function index()
    {
        $attendance = Attendance::paginate(9);
        return dataResponse(200,'Routine',$attendance);
    }
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'batch_id' => 'required|integer',
            'date' => 'required|date',
            'attendance_by' => 'required|integer', // Assuming this is the teacher's ID
            'attendances' => 'required|array',
            'attendances.*.student_id' => 'required|integer',
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
