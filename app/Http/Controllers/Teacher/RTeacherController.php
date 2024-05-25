<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\TeacherRequest;
use App\Models\Teacher;
use App\Models\User;
use App\Services\TeacherService;
use http\Env\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
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
    public function show(string $id)
    {
        //
        $teachers = Teacher::with('user')->where('id',$id)->first();
        return response()->json(['message' => 'Teacher', 'teacher' => $teachers],200);
    }

    public function edit(string $id)
    {
        //

    }

    public function update(Request $request, string $id):JsonResponse
    {

        try {
            $teacher = $this->teacherService->updateTeacher($request->all(), $id);
            return response()->json(['message' => 'Teacher updated successfully', 'teacher' => $teacher], 200);

        } catch (ModelNotFoundException $e) {
            return response()->json(['message' => 'Teacher not found', 'error' => $e->getMessage()], 404);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Failed to update teacher', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        //
    }
}
