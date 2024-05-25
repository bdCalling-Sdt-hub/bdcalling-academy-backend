<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeacherRequest;
use App\Models\Teacher;
use App\Models\User;
use App\Services\TeacherService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RTeacherController extends Controller
{

    public function index()
    {
        //
        $teachers = Teacher::with('user')->paginate(9);
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
            $teacher = $this->teacherService->createTeacher($request->validated());

            return response()->json(['message' => 'Teacher added successfully', 'teacher' => $teacher], 201);

        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to add teacher', 'error' => $e->getMessage()], 500);
        }
    }
//    public function store(TeacherRequest $request)
//    {
//        DB::beginTransaction();
//
//        try {
//            $user = User::create([
//                'name' => $request->name,
//                'email' => $request->email,
//                'password' => bcrypt($request->password), // Hash the password
//                'otp' => 0,
//            ]);
//
//            $teacher = Teacher::create([
//                'user_id' => $user->id,
//                'course_category_id' => $request->course_category_id,
//                'phone_number' => $request->phone_number,
//                'designation' => $request->designation,
//                'expert' => $request->expert,
//                'created_by' => 'super admin add', // Assuming you're using authentication
//                'status' => 'active',
//            ]);
//
//            DB::commit();
//
//            return response()->json(['message' => 'Teacher added successfully', 'teacher' => $teacher], 201);
//
//        } catch (\Exception $e) {
//            DB::rollBack();
//            return response()->json(['message' => 'Failed to add teacher', 'error' => $e->getMessage()], 500);
//        }
//    }
    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
