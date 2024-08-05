<?php

namespace App\Http\Controllers;

use App\Http\Requests\AddEmployerRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AddEmployeeController extends Controller
{

    public function index(Request $request)
    {
        $query = User::where('role','ADMIN');

        if ($request->filled('name')){
            $query->where('name'. 'like' , '%' . $request->input('name') . '%');
        }
        $admin = $query->paginate(8);
        return response()->json(['message' => 'Admin', 'data' => $admin]);
    }

    public function showSuperAdmin(Request $request)
    {
        $query = User::where('role','SUPER ADMIN');

        if ($request->filled('name')){
            $query->where('name' , 'like' , '%' . $request->input('name') . '%');
        }
        $super_admin = $query->paginate(8);
        return response()->json(['message' => 'Super Admin', 'data' => $super_admin]);
    }


    public function store(AddEmployerRequest $request)
    {
        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;

        if ($request->file('image')) {
            $user->image = saveImage($request,'image');
        }

        $user->role = $request->role;
        $user->otp = 0;
        $user->designation = $request->designation ?? null;
        $user->expertise = $request->expertise ?? null;
        $user->email_verified_at = new Carbon();
        $user->password = Hash::make($request->password);
        $user->save();

        return response()->json([
            'message' => 'Role added successfully',
            'user' => $user,
        ], 200);
    }

    public function show(string $id)
    {
        $admin_user = User::where('role', 'ADMIN')->where('id',$id)->paginate(8);
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
        if ($request->file('image')) {
            if (!empty($user->image)) {
                removeImage($admin_user->image);
            }
            $admin_user->image = saveImage($request,'image');
        }
        $admin_user->name = $request->name ?? $admin_user->name;
        $admin_user->email = $request->email ?? $admin_user->email;
        $admin_user->phone_number = $request->phone_number ?? $admin_user->phone_number;
        $admin_user->designation = $request->designation ?? $admin_user->designation;
        $admin_user->expertise = $request->expertise ?? $admin_user->expertise;
        $admin_user->password = $request->password ?? $admin_user->password;
        $admin_user->update();
        return response()->json(['message' => 'Admin/Super Admin Updated Successfully']);
    }

    public function destroy(string $id)
    {
        //
        $admin_user = User::where('id', $id)->first();
        if ($admin_user) {
            $admin_user->forcedelete();
            return response()->json([
                'message' => 'Admin or Super Admin deleted successfully'
            ]);
        }else{
            return response()->json(['message' => 'Admin/Super Admin Does Not Exist'],404);
        }
    }

}
