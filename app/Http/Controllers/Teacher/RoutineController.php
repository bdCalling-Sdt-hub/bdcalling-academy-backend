<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\RoutineRequest;
use App\Models\Routine;
use Illuminate\Http\Request;

class RoutineController extends Controller
{

    public function index(Request $request)
    {
        $module_title = $request->input('module_title');
        $batch_id = $request->input('batch_id');
        $date = $request->input('date');

        $query = Routine::with('batch','course_module')->get();

        if ($module_title) {
            $query->where('module_title', 'like', '%' . $module_title . '%');
        }
        if ($batch_id) {
            $query->where('batch_id', $batch_id);
        }
        if ($date) {
            $query->whereDate('date', $date);
        }

        // Paginate the results
        $routine = $query->paginate(9);

        return response()->json(['message' => 'Routine retrieved successfully', 'data' => $routine]);
    }


    public function create()
    {
        //
    }

    public function store(RoutineRequest $request)
    {
        $routine = new Routine();
        $routine->batch_id = $request->batch_id;
        $routine->course_module_id = $request->course_module_id;
        $routine->course_id = $request->course_id;
        $routine->date = $request->date;
        $routine->time = $request->time;
        $routine->save();
        return response()->json(['message' => 'Routine Added Successfully']);
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
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
