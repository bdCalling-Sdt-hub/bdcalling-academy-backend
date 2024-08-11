<?php

namespace App\Http\Controllers;

use App\Http\Requests\FollowUpRequest;
use App\Models\Student;
use App\Notifications\FollowUpMessage;

class FollowUpController extends Controller
{

//    public function followUpMessage(FollowUpRequest $request)
//    {
//        $message = $request->message;
//        $ids = json_decode($request->ids);
//
//        $students = Student::whereIn('id', $ids)->get();
//
//        if ($students->isEmpty()) {
//            return response()->json(['message' => 'No Students Found'], 404);
//        }
//
//        foreach ($students as $student) {
//            $student->notify(new FollowUpMessage($student->phone_number, $message));
//            $student->messages = $request->messages;
//            $student->save();
//        }
//
//        return response()->json([
//            'message' => 'Notifications sent successfully',
//            'text' => $message,
//            'phone_numbers' => $students->pluck('phone_number')
//        ], 200);
//    }
    public function followUpMessage(FollowUpRequest $request)
    {
        $message = $request->messages;
        $ids = json_decode($request->ids);

        $students = Student::whereIn('id', $ids)->get();

        if ($students->isEmpty()) {
            return response()->json(['message' => 'No Students Found'], 404);
        }

        foreach ($students as $student) {
          //  $student->notify(new FollowUpMessage($student->phone_number, $message));

            // Retrieve the existing messages
            $existingMessages = $student->messages ?: [];

            // Append the new message to the existing messages
            $existingMessages[] = $message;

            // Save the updated messages back to the student
            $student->messages = $existingMessages;
            $student->save();
        }

        return response()->json([
            'message' => 'Notifications sent successfully',
            'text' => $message,
            'phone_numbers' => $students->pluck('phone_number')
        ], 200);
    }

}
