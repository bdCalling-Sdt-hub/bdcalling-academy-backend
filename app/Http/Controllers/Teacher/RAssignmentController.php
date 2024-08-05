<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssignmentRequest;
use App\Models\Assignment;
use Illuminate\Http\Request;

class RAssignmentController extends Controller
{

    public function index(Request $request)
    {
        $query = Assignment::with('batch');

        if ($request->filled('batch_id')){
            $query->whereHas('batch',function ($q) use ($request){
                $q->where('batch_id',$request->batch_id);
            });
        }
        if ($request->filled('date')){
            $query->where('date',$request->date);
        }

        if ($request->filled(' ')){
            $query->where('assignment_name', $request->assignment_name);
        }
        $assignment = $query->paginate(12);

        return response()->json(['data' => $assignment],200);
    }

    public function create()
    {

    }

    public function store(AssignmentRequest $request)
    {
        $assignment = Assignment::create($request->all());
        return response()->json(['data' => $assignment]);
    }

    public function show(string $id)
    {

    }

    public function edit(string $id)
    {

    }

    public function update(Request $request, string $id)
    {
       $assignment = Assignment::find($id);
       if (empty($assignment)){
           return response()->json(['message' => 'Assignment Does Not Exist'],404);
       }
       $assignment->time = $request->time ?? $assignment->time;
       $assignment->date = $request->date ?? $assignment->date;
       $assignment->assignment_name = $request->assignment_name ?? $assignment->assignment_name;
       $assignment->question_link = $request->question_link ?? $assignment->question_link;
       $assignment->update();

       return response()->json(['message' => 'Assignment Updated Successfully', 'data' => $assignment]);
    }

    public function destroy(string $id)
    {
        $assignment = Assignment::find($id);
        if (empty($assignment)){
            return response()->json(['message' => 'Assignment Does Not Exist'],404);
        }
        $assignment->delete();
        return response()->json(['message' => 'Assignment Deleted Successfully']);
    }
}
