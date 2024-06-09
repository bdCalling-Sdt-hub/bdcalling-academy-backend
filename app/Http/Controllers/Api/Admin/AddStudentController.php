<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddStrudentRequest;
use App\Models\AddStudent;
use App\Models\User;
use Illuminate\Http\Request;
use Hash;
use App\Mail\SendOtp;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Notifications\AppNotifications;
class AddStudentController extends Controller
{
    public function addStudent(AddStrudentRequest $request)
    {
        $email = $request->email;
        $courseId = $request->course_id;
        $batchId = $request->batch_id;
        $random = Str::random(6);
        $date = Carbon::now();
        $check = User::where('email', $email)->first();
        $user = null;

        if (!$check) {
            // Create a new user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('1234567rr'),
                'otp' => $random,
                'role' => 'STUDENT',
                'email_verified_at' => $date
            ]);

            // Send OTP to the new user
            Mail::to($email)->send(new SendOtp($random));

            $userId = $user->id;
            $isNewUser = true;
        } else {
            $userId = $check->id;
            $user = $check;
        }

        // Check if the student record already exists
        $addStudent = AddStudent::where('user_id', $userId)->where('course_id', $courseId)->where('batch_id', $batchId )->first();

        $studentData = [
            'category_id' => $request->category_id,
            'batch_id' => $request->batch_id,
            'user_id' => $userId,
            'course_id' => $request->course_id,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'riligion' => $request->riligion,
            'registration_date' => $request->registration_date,
            'dob' => $request->dob,
            'blood_group' => $request->blood_group,
            'address' => $request->address,
            'add_by' => $request->add_by,
            'student_type' => $request->student_type,
        ];

        if ($addStudent) {
            // Update the existing student record
            $addStudent->update($studentData);
            $user->notify(new AppNotifications('Update your profile', $userId));
            $message = 'User information updated successfully';
        } else {
            // Create a new student record
            $addStudent = AddStudent::create($studentData);
            $message = 'Student added successfully';
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $addStudent,
        ], 200);
    }

    public function show_notifications()
    {
        //
    }

}