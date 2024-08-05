<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewStudentAdmitRequest;
use App\Http\Requests\UpdateStudentRequest;
use App\Models\Batch;
use App\Models\BatchStudent;
use App\Models\Student;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PhoenixStudentController extends Controller
{
    //

    public function admitPhoenixStudent(NewStudentAdmitRequest $request)
    {
        DB::beginTransaction();
        try {
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->otp = 0;
            $user->role = 'STUDENT';
            $user->email_verified_at = new Carbon();
            $user->save();

            $student = new Student();
            $student->user_id = $user->id;
            $student->category_id = $request->category_id;
            $student->phone_number = $request->phone_number;
            $student->gender = $request->gender;
            $student->religion = $request->religion;
            $student->registration_date = $request->registration_date;
            $student->dob = $request->dob;
            $student->blood_group = $request->blood_group;
            $student->address = $request->address;
            $student->add_by = $request->add_by;
            $student->student_type = $request->student_type; //here student type should be phoenix
            if ($request->file('image')) {
                if (!empty($student->image)) {
                    removeImage($student->image);
                }
                $student->image = saveImage($request, 'image');
            }
            $student->save();

            // Insert into batch_students table
            $batch_student = new BatchStudent();
            $batch_student->batch_id = $request->batch_id;
            $batch_student->student_id = $student->id;
            $batch_student->status = 'enrolled';
            $batch_student->save();

            DB::commit();
            return response()->json(['message' => 'Student added and assigned to batch successfully', 'data' => $student], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Failed to add and assign student', 'error' => $e->getMessage()], 500);
        }
    }

    public function updatePhoenixStudent(UpdateStudentRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $student = Student::findOrFail($id);
            $user = User::findOrFail($student->user_id);

            $user->name = $request->name ?? $user->name;
            $user->email = $request->email ?? $user->email;
            if ($request->has('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();

            $student->category_id = $request->category_id ?? $student->category_id;
            $student->phone_number = $request->phone_number ?? $student->phone_number;
            $student->gender = $request->gender ?? $student->gender;
            $student->religion = $request->religion ?? $student->religion;
            $student->registration_date = $request->registration_date ?? $student->registration_date;
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

            if ($request->has('batch_id')) {
                $batch_student = BatchStudent::where('student_id', $student->id)->first();
                if ($batch_student) {
                    $batch_student->batch_id = $request->batch_id ?? $batch_student->batch_id;
                    $batch_student->save();
                }
            }

            DB::commit();
            return response()->json(['message' => 'Student updated successfully', 'data' => $student], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Failed to update student', 'error' => $e->getMessage()], 500);
        }
    }
    public function destroyPhoenixStudent($id)
    {
        DB::beginTransaction();
        try {
            $student = Student::findOrFail($id);
            $user = User::findOrFail($student->user_id);

            BatchStudent::where('student_id', $student->id)->delete();

            if (!empty($student->image)) {
                removeImage($student->image);
            }
            $student->delete();

            $user->delete();

            DB::commit();
            return response()->json(['message' => 'Student deleted successfully'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['message' => 'Failed to delete student', 'error' => $e->getMessage()], 500);
        }
    }
    public function applicationForPhoenixBatch(Request $request)
    {
        return $request;
    }

    public function showPhoenixStudent(Request $request)
    {
        $query = Batch::with(['students.user', 'course.course_category'])
            ->has('students')->where('batch_id','like','%'. 'Phoenix'. '%');

        if ($request->filled('name')) {
            $query->whereHas('students.user', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->input('name') . '%');
            });
        }

        if ($request->filled('phone_number')) {
            $query->whereHas('students', function($q) use ($request) {
                $q->where('phone_number',$request->input('phone_number'));
            });
        }

        if ($request->filled('registration_date')) {
            $query->whereHas('students', function($q) use ($request) {
                $q->where('registration_date', $request->input('registration_date'));
            });
        }

        if ($request->filled('category_name')) {
            $query->whereHas('course.category', function($q) use ($request) {
                $q->where('category_name', $request->input('category_name'));
            });
        }

        if ($request->filled('batch_id')) {

            $query->where('batch_id', $request->input('batch_id'));
        }

        return $query->paginate(12);
    }


}
