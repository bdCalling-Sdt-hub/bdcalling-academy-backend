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
        $subject = $request->subject;
        $batch = $request->batch;
        $date = $request->date;

        $routine = Routine::paginate(9);
        return response()->json(['message'=> 'Routine' , 'data' => $routine]);
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
