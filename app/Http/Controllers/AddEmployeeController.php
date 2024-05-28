<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddEmployerRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AddEmployeeController extends Controller
{

    public function index()
    {
        $employer = User::where('role','ADMIN')->get();
        return response()->json(['message' => 'Admin' , 'data' => $employer]);
    }

    public function store(AddEmployerRequest $request)
    {
        //
        $user = new User();
        $validatedData['role'] = 'ADMIN';
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->role = $validatedData['role'];
        $user->otp = 0;
        $user->email_verified_at = 1;
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'message' => 'Admin added successfully',
            'user' => $user, // Optionally, you can return the created user data
        ], 200);

    }

    public function show(string $id)
    {
        $admin_user = User::where('role', 'ADMIN')->where('id',$id)->first();
        if ($admin_user) {
            return response()->json([
                'message' => 'Admin',
                'data' => $admin_user,
            ]);
        } else {
            return response()->json([
                'message' => 'Admin Does Not Exist',
                'data' => $admin_user,
            ],404);
        }
    }

    public function update(Request $request, string $id)
    {
        $admin_user = User::find($id);
        if (empty($admin_user)){
            return response()->json(['message' => 'User Does Not Exist',],404);
        }
        $admin_user->name = $request->name ?? $admin_user->name;
        $admin_user->email = $request->email ?? $admin_user->email;
        $admin_user->password = $request->password ?? $admin_user->password;
        $admin_user->update();
        return response()->json(['message' => 'Admin Updated Successfully']);
    }

    public function destroy(string $id)
    {
        //
        $admin_user = User::where('role', 'ADMIN')->where('id', $id)->first();
        if ($admin_user) {
            $admin_user->forcedelete();
            return response()->json([
                'message' => 'Admin deleted successfully'
            ]);
        }
    }
}
