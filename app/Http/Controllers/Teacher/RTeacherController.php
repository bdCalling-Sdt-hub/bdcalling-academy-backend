<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeacherRequest;
use App\Http\Requests\UpdateTeacherRequest;
use App\Models\Batch;
use App\Models\BatchTeacher;
use App\Models\LeaveApplication;
use App\Models\Teacher;
use App\Models\User;
use App\Services\TeacherService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class RTeacherController extends Controller
{

    public function index(Request $request)
    {
        $query = Teacher::with('user.teacher','category');
        if ($request->filled('no_pagination')){
            $teachers = $query->get();
            return response()->json(['message' => 'Teacher List', 'teacher' => $teachers],200);
        }
        $teachers = $query->paginate(8);
        return response()->json(['message' => 'Teacher List', 'teacher' => $teachers],200);
    }

    public function create()
    {
        //
    }

    protected $teacherService;

    public function __construct(TeacherService $teacherService)
    {
        $this->teacherService = $teacherService;
    }

    public function store(TeacherRequest $request): JsonResponse
    {
        try {
            $teacher = $this->teacherService->createTeacher($request);

            return response()->json(['message' => 'Teacher added successfully', 'teacher' => $teacher], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to add teacher', 'error' => $e->getMessage()], 500);
        }
    }
    public function show(string $id)
    {
        $teachers = Teacher::with('user')->where('id',$id)->first();
        return response()->json(['message' => 'Teacher', 'teacher' => $teachers],200);
    }

    public function edit(string $id)
    {

    }

    public function update(UpdateTeacherRequest $request, string $id)
    {

        try {
            $data = $request->all();

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image');
            }

            $teacher = $this->teacherService->updateTeacher($request, $id);
            return response()->json(['message' => 'Teacher updated successfully', 'teacher' => $teacher], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Teacher not found', 'error' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update teacher', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        $teacher = Teacher::find($id);
        if (!$teacher) {
            return response()->json(['error' => 'Teacher not found'], 404);
        }
        DB::transaction(function() use ($teacher) {

            $teacher->user()->delete();
            $teacher->delete();
        });
        return response()->json(['message' => 'Teacher, associated user, and media deleted successfully'], 200);
    }

    public function showLeaveApplication(Request $request)
    {
        try {
            $leave_applications = $this->teacherService->getFilteredLeaveApplications($request);
            return response()->json(['message' => 'Leave Application List', 'data' => $leave_applications]);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Something went wrong!', 'error' => $e->getMessage()], 500);
        }
    }

    public function approveLeaveRequest(Request $request)
    {
        return $this->teacherService->approveLeave($request);
    }

    public function rejectLeaveRequest(Request $request)
    {
       return $this->teacherService->rejectLeave($request);
    }

    public function showAssignModule(Request $request)
    {
        $batch_id = $request->batch_id;
        return $teacher = Batch::with('course.course_module','teachers')->where('id',$batch_id)->first();
    }
}
