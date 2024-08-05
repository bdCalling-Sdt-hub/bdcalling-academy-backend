<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoutineRequest;
use App\Models\BatchTeacher;
use App\Models\Routine;
use Illuminate\Http\Request;

class RoutineController extends Controller
{

    public function index(Request $request)
    {
        $module_title = $request->input('module_title');
        $batch_id = $request->input('batch_id');
        $date = $request->input('date');

        $query = Routine::with('batch','course_module');

        if ($module_title) {
            $query->whereHas('course_module',function ($q) use ($module_title){
                $q->where('module_title', 'like', '%' . $module_title . '%');
            });
        }
        if ($batch_id) {
            $query->whereHas('batch',function ($q) use ($batch_id){
                $q->where('batch_id', 'like', '%' . $batch_id . '%');
            });
        }
        if ($date) {
            $query->whereDate('date', $date);
        }

        if ($request->filled('teacher_id')) {
            $teacher_id = $request->teacher_id;
            $batch_teacher = BatchTeacher::where('teacher_id', $teacher_id)->get();
            $course_ids = $batch_teacher->map(function ($item) {
                return $item->batch->course_id;
            });
            $query->whereIn('course_id', $course_ids);
        }

        // Paginate the results
        $routine = $query->paginate(9);
        return response()->json(['message' => 'Routine retrieved successfully', 'data' => $routine]);
    }
    public function store(RoutineRequest $request)
    {
        // Check if there is already a routine for the same batch, date, and time
        $existingRoutine = Routine::where('batch_id', $request->batch_id)
            ->where('date', $request->date)
            ->where('time', $request->time)
            ->first();

        if ($existingRoutine) {
            return response()->json(['message' => 'You already set a class in this time'], 400);
        }

        $routine = new Routine();
        $routine->batch_id = $request->batch_id;
        $routine->course_module_id = $request->course_module_id;
        $routine->course_id = $request->course_id;
        $routine->date = $request->date;
        $routine->time = $request->time;
        $routine->save();

        return response()->json(['message' => 'Routine Added Successfully','data' => $routine]);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        // Find the routine by ID
        $routine = Routine::find($id);
        if (!$routine) {
            return response()->json(['message' => 'Routine not found'], 404);
        }

        $existingRoutine = Routine::where('batch_id', $routine->batch_id)
            ->where('date', $request->date)
            ->where('time', $request->time)
            ->where('id', '!=', $id)
            ->first();

        if ($existingRoutine) {
            return response()->json(['message' => 'You already set a class in this time'], 400);
        }
        $routine->date = $request->date;
        $routine->time = $request->time;
        $routine->update();

        return response()->json(['message' => 'Routine updated successfully', 'data' => $routine]);
    }

    public function destroy(string $id)
    {
        $routine = Routine::find($id);
        if (!$routine) {
            return response()->json(['message' => 'Routine not found'], 404);
        }
        $routine->delete();

        return response()->json(['message' => 'Routine deleted successfully']);
    }

}
