<?php
namespace App\Services;

use App\Http\Requests\TeacherRequest;
use App\Models\LeaveApplication;
use App\Models\User;
use App\Models\Teacher;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class TeacherService
{
    public function createTeacher(Request $request)
    {
        DB::beginTransaction();

        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->otp = 0;
            $user->role = 'MENTOR';
            $user->email_verified_at = new Carbon();
            $user->save();

            // Insert into teachers table
            $teacher = new Teacher();
            $teacher->user_id = $user->id;
            $teacher->course_category_id = $request->course_category_id;
            $teacher->phone_number = $request->phone_number;
            $teacher->designation = $request->designation;
            $teacher->expert = $request->expert;
            $teacher->created_by = $request->created_by ?? null;
            $teacher->status = 'active';
            $teacher->payment_type = $request->payment_type;
            $teacher->payment_method = $request->payment_method;
            $teacher->payment = $request->payment;

            if ($request->file('image')) {
                if (!empty($teacher->image)) {
                    removeImage($teacher->image);
                }
                $teacher->image = saveImage($request, 'image');
            }

            $teacher->save();

            DB::commit();
            return $teacher;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating teacher: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateTeacher(Request $request, string $id)
    {
        DB::beginTransaction();
        try {
            // Find the teacher record
            $teacher = Teacher::findOrFail($id);
            // Find the corresponding user record
            $user = User::findOrFail($teacher->user_id);

            // Update user details
            $user->name = $request->name ?? $user->name;
            $user->email = $request->email ?? $user->email;
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            // Update teacher details
            $teacher->course_category_id = $request->course_category_id ?? $teacher->course_category_id;
            $teacher->phone_number = $request->phone_number ?? $teacher->phone_number;
            $teacher->designation = $request->designation ?? $teacher->designation;
            $teacher->expert = $request->expert ?? $teacher->expert;
            $teacher->created_by = $request->created_by ?? $teacher->created_by;
            $teacher->status = $request->status ?? $teacher->status;
            $teacher->payment_type = $request->payment_type ?? $teacher->payment_type;
            $teacher->payment_method = $request->payment_method ?? $teacher->payment_method;
            $teacher->payment = $request->payment ?? $teacher->payment;

            if ($request->file('image')) {
                if (!empty($teacher->image)) {
                    removeImage($teacher->image);
                }
                $teacher->image = saveImage($request, 'image');
            }

            $teacher->save();

            DB::commit();
            return $teacher;
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error updating teacher: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getFilteredLeaveApplications(Request $request)
    {
        $query = LeaveApplication::with('user.teacher');

        // Apply filters
        if ($request->filled('name')) {
            $query->whereHas('user.teacher', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('name') . '%');
            });
        }

        if ($request->filled('phone_number')) {
            $query->whereHas('user.teacher', function($q) use ($request) {
                $q->where('phone_number', 'like', '%' . $request->input('phone_number') . '%');
            });
        }

        if ($request->filled('designation')) {
            $query->whereHas('user.teacher', function($q) use ($request) {
                $q->where('designation', 'like', '%' . $request->input('designation') . '%');
            });
        }

        if ($request->filled('date')) {
            $query->whereDate('created_at', 'like', '%' . $request->input('date') . '%');
        }

        if ($request->filled('leave_status')) {
            $query->where('leave_status', 'like','%'  . $request->input('leave_status') . '%' );
        }
        if ($request->filled('auth_type')) {
            $teacher_id = auth()->user()->id;
            $query->where('user_id', $teacher_id);
        }
        return $query->paginate(9);
    }

    public function rejectLeave(Request $request)
    {
        try{
            $id = $request->id;
            $approve_application = LeaveApplication::find($id);
            if (empty($approve_application))
            {
                return response()->json(['message' => 'No application request found', 'data' => $approve_application],404);
            }
            $approve_application->recommend_by = $request->recommend_by ?? $approve_application->recommend_by;
            $approve_application->leave_status = 'rejected';
            $approve_application->update();
            return response()->json(['message' => 'Leave application is rejected successfully', 'data' => $approve_application ],200);
        }catch (\Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!', 'error' => $e->getMessage()],500);
        }
    }

    public function approveLeave(Request $request)
    {
        try{
            $id = $request->id;
            $approve_application = LeaveApplication::find($id);
            if (empty($approve_application))
            {
                return response()->json(['message' => 'No application request found', 'data' => $approve_application],404);
            }
            $approve_application->recommend_by = $request->recommend_by ?? $approve_application->recommend_by;
            $approve_application->leave_status = 'approved';
            $approve_application->update();
            return response()->json(['message' => 'Leave application is approved successfully', 'data' => $approve_application ],200);
        }catch (\Exception $e) {
            return response()->json(['message' => 'Something Went Wrong!', 'error' => $e->getMessage()],500);
        }
    }
}
