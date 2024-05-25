<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddStrudentRequest;
use App\Models\AddStudent;
use App\Models\User;
use Illuminate\Http\Request;
use Hash;
use App\Mail\SendOtp;
use Mail;
use Illuminate\Support\Str;
use Carbon\Carbon;
class AddStudentController extends Controller
{
    public function addStudent(AddStrudentRequest $request)
    {
        $email = $request->email;
        $random = Str::random(6);
        $date = Carbon::now();
        $check = User::where('email', $email)->first();
        
        if (!$check) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('123456789'),
                'otp' => $random,
                'email_verified_at' => $date
            ]);
    
            Mail::to($email)->send(new SendOtp($random));
    
            $addStudent = AddStudent::create($request->validated());
    
            return response()->json([
                'status' => 'success',
                'message' => 'add student created successfully',
                'data' => $addStudent,
            ], 200);
        } else {
            // Assuming you want to update the existing AddStudent information
            $addStudent = AddStudent::where('user_id', $check->id)->first();
    
            if ($addStudent) {
                $addStudent->update($request->validated());
            } else {
                // If there's no existing AddStudent entry, create a new one
                $addStudent = AddStudent::create($request->validated());
            }
    
            return response()->json([
                'status' => 'success',
                'message' => 'User information updated successfully',
                'data' => $addStudent,
            ], 200);
        }
    }
    
}
