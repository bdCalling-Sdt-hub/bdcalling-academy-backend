<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\FeedbackRequest;
use App\Models\StudentFeedback;
use Illuminate\Http\Request;

class FeedbackController extends Controller
{

    public function index()
    {
        return $student_feedback = StudentFeedback::with('user.student')->paginate(9);
    }


    public function create()
    {
        //
    }

    public function store(FeedbackRequest $request)
    {
        try {
            $student_feedback = new StudentFeedback();
            $student_feedback->user_id = $request->user_id;
            $student_feedback->feedback = $request->feedback;
            $student_feedback->save();
            return response()->json(['message' => 'Student Feedback Add Successfully'],200);
        }catch (\Exception $e){
            return $e->getMessage();
        }
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
        $feedback = StudentFeedback::find($id);
        if ($feedback)
        {
            $feedback->feedback = $request->feedback ?? $feedback->feedback;
            $feedback->update();
            return response()->json(['message' => 'Feedback Updated Successfully' , 'data' => $feedback],200);
        }else {
            return response()->json(['message' => 'User Does Not Exist'],404);
        }
    }

    public function destroy(string $id)
    {
        //
    }

    public function showFeedback()
    {
        $user_id = auth()->user()->id;
        $student_feedback = StudentFeedback::with('user.student')->where('user_id',$user_id)->paginate(9);
        if ($user_id){
            return response()->json(['message' => 'feedback', 'data' => $student_feedback ], 200);
        }else
        {
            return response()->json(['message' => 'Unauthorized User','data' => $student_feedback]);
        }

    }
}
