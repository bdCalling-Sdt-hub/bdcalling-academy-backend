<?php
namespace App\Services;

use App\Models\LeaveApplication;
use App\Models\User;
use App\Models\Teacher;
use Carbon\Carbon;
use http\Env\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Exception;

class TeacherService
{
    public function createTeacher(array $data)
    {
        DB::beginTransaction();

        try {
        // Create the user
            $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'otp' => 0,
            'role' => $data['role'],
        ]);

        $user->update(['email_verified_at' => now()]);

        // Create the teacher
        $teacher = Teacher::create([
            'user_id' => $user->id,
            'course_category_id' => $data['course_category_id'],
            'phone_number' => $data['phone_number'],
            'designation' => $data['designation'],
            'expert' => $data['expert'],
            'created_by' => $data['created_by'] ?? null, // Assuming you're using authentication
            'status' => 'active',
        ]);

        // Commit the transaction
        DB::commit();

        return $teacher;

        } catch (Exception $e) {
        // Rollback the transaction and log the error
            DB::rollBack();
            Log::error('Error creating teacher: ' . $e->getMessage());
            throw $e;
        }
    }

    public function updateTeacher(array $data, string $id)
    {

        DB::beginTransaction();

        try {
            // Find the teacher
            $teacher = Teacher::findOrFail($id);

            // Update the user
            $user = $teacher->user;
            $user->update([
                'name' => $data['name'] ?? $teacher->name,
                'email' => $data['email'] ?? $teacher->email,
                // Only update the password if provided
                'password' => isset($data['password']) ? bcrypt($data['password']) : $user->password,
            ]);

            // Update the teacher
            $teacher->update([
                'course_category_id' => $data['course_category_id'],
                'phone_number' => $data['phone_number'] ?? $teacher->phone_number,
                'designation' => $data['designation'] ?? $teacher->designation,
                'expert' => $data['expert'] ?? $teacher->expert,
                'status' => $data['status'] ?? $teacher->status,
                'created_by' => $data['created_by'] ?? $teacher->created_by,
            ]);

            // Commit the transaction
            DB::commit();

            return $teacher;

        } catch (Exception $e) {
            // Rollback the transaction and log the error
            DB::rollBack();
            Log::error('Error updating teacher: ' . $e->getMessage());
            throw $e;
        }
    }

    public function getFilteredLeaveApplications(Request $request)
    {
        $query = LeaveApplication::with('user.teacher');

        // Apply filters
        if ($request->has('name')) {
            $query->whereHas('user.teacher', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('name') . '%');
            });
        }

        if ($request->has('phone_number')) {
            $query->whereHas('user.teacher', function($q) use ($request) {
                $q->where('phone_number', 'like', '%' . $request->input('phone_number') . '%');
            });
        }

        if ($request->has('designation')) {
            $query->whereHas('user.teacher', function($q) use ($request) {
                $q->where('designation', 'like', '%' . $request->input('designation') . '%');
            });
        }

        if ($request->has('leave_status')) {
            $query->where('leave_status', 'like','%'  . $request->input('leave_status') . '%' );
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
