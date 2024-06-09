<?php

namespace App\Http\Controllers;

use App\Http\Requests\FollowUpRequest;
use App\Models\Student;
use App\Notifications\FollowUpMessage;
use Illuminate\Http\Request;

class FollowUpController extends Controller
{
    //

//    public function followUpMessage(FollowUpRequest $request)
//    {
//        $message = $request->message;
//        $id = $request->id;
//        $student = Student::where('id',$id)->first();
//        if (empty($student)){
//            return response()->json(['message' => 'Student Does Not Exist'],404);
//        }
//        $student->notify(new FollowUpMessage($student->phone_number , $message));
//        return response()->json(['message' => 'Notification sent successfully','text' => $message, 'phone_number' => $student->phone_number], 200);
//
//    }

    public function followUpMessage(FollowUpRequest $request)
    {
        $message = $request->message;
        $ids = json_decode($request->ids);

        $students = Student::whereIn('id', $ids)->get();

        if ($students->isEmpty()) {
            return response()->json(['message' => 'No Students Found'], 404);
        }

        foreach ($students as $student) {
            $student->notify(new FollowUpMessage($student->phone_number, $message));
        }

        return response()->json([
            'message' => 'Notifications sent successfully',
            'text' => $message,
            'phone_numbers' => $students->pluck('phone_number')
        ], 200);
    }

}
