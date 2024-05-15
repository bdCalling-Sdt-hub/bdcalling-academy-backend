<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddStrudentRequest;
use App\Models\AddStudent;
use App\Models\User;
use Illuminate\Http\Request;
use Hash;

class AddStudentController extends Controller
{
    public function addStudent(AddStrudentRequest $request)
    {
        $email = $request->email;
        $check = User::where('email', $email)->first();
        if (!$check) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make('123456789'),
            ]);
            $addStuent = AddStudent::create($request->validated());

            return response()->json([
                'status' => 'success',
                'message' => 'add studnet created successfully',
                'data' => $addStuent,
            ], 200);
        } else {
            return response()->json([
                'message' => 'User all ready regsterd'
            ]);
        }
    }
}
