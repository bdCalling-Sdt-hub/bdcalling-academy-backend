<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\StudentRequest;
use App\Models\BatchStudent;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller
{
    public function index(Request $request)
    {
        $query = Student::with('user','category');

        if ($request->filled('dob')) {
            $query->where('dob', 'like', '%' . $request->input('dob') . '%');
        }
        if ($request->filled('name')) {
            $name = $request->input('name');
            $query->whereHas('user',function ($q) use ($name){
                $q->where('name', 'like', '%' . $name . '%');
            });
        }
        if ($request->filled('phone_number')) {
            $phone = $request->input('phone_number');
                $query->where('phone_number', 'like', '%' . $phone . '%');
            }
        if ($request->filled('student_type')) {
            $student_type = $request->input('student_type');
            $query->where('student_type',$student_type);
        }

        if ($request->filled('event_name')) {
            $event_name = $request->input('event_name');
            $query->where('event_name', 'like', '%' . $event_name . '%');
        }

        if ($request->filled('category_name')) {
            $category = $request->input('category_name');
            $query->whereHas('category',function ($q) use ($category){
                $q->where('category_name', 'like', '%' . $category . '%');
            });
        }

        if ($request->filled('category_id')) {
            $category = $request->input('category_id');
                $query->where('category_id',$category);
        }

        if ($request->filled('add_by')) {
            $add_by = $request->input('add_by');
            $query->where('add_by', $add_by);
        }

        if ($request->filled('date')) {
            $date = $request->input('date');
            $query->where('registration_date', $date);
        }

        $students = $query->paginate(10);

        return $students;
    }

    public function store(StudentRequest $request)
    {
        DB::beginTransaction();
        try {
            // Insert into users table
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->otp = 0;
            $user->role = 'STUDENT';
            $user->email_verified_at = new Carbon();
            $user->save();

            // Insert into students table
            $student = new Student();
            $student->user_id = $user->id;
            $student->category_id = $request->category_id;
            $student->phone_number = $request->phone_number;
            $student->gender = $request->gender;
            $student->religion = $request->religion;
            $student->registration_date = $request->registration_date;
            $student->dob = $request->dob ?? null;
            $student->blood_group = $request->blood_group;
            $student->address = $request->address;
            $student->add_by = auth()->user()->name;
            $student->student_type = $request->student_type;
            $student->event_name = $request->event_name ?? null;
            if ($request->file('image')) {
                if (!empty($student->image)) {
                    removeImage($student->image);
                }
                $student->image = saveImage($request,'image');
            }
            $student->save();

            DB::commit();
            return response()->json(['message' => 'Student added successfully','data' => $student], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Failed to add student', 'error' => $e->getMessage()], 500);
        }
    }

    public function show(string $id)
    {

    }

    public function update(Request $request, string $id)
    {
        DB::beginTransaction();
        try {
            $student = Student::findOrFail($id);
            $user = User::findOrFail($student->user_id);

            $user->name = $request->name ?? $user->name;
            $user->email = $request->email ?? $user->email;
            if ($request->password) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            $student->category_id = $request->category_id ?? $student->category_id;
            $student->phone_number = $request->phone_number ?? $student->phone_number;
            $student->gender = $request->gender ?? $student->gender;
            $student->religion = $request->religion ?? $student->religion;
            $student->registration_date = $request->registration_date ?? $student->registration_date ;
            $student->dob = $request->dob ?? $student->dob;
            $student->blood_group = $request->blood_group ?? $student->blood_group;
            $student->address = $request->address ?? $student->address;
            $student->add_by = $request->add_by ?? $student->add_by;
            $student->student_type = $request->student_type ?? $student->student_type;

            if ($request->file('image')) {
                if (!empty($student->image)) {
                    removeImage($student->image);
                }
                $student->image = saveImage($request, 'image');
            }
            $student->save();

            DB::commit();
            return response()->json(['message' => 'Student updated successfully', 'data' => $student], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Failed to update student', 'error' => $e->getMessage()], 500);
        }
    }

    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $student = Student::findOrFail($id);

            $user = User::findOrFail($student->user_id);

            if (!empty($student->image)) {
                removeImage($student->image);
            }
            $student->delete();

            $user->delete();

            DB::commit();
            return response()->json(['message' => 'Student and associated user deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Failed to delete student', 'error' => $e->getMessage()], 500);
        }
    }

}
