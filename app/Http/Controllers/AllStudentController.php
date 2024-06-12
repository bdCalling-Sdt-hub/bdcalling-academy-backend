<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AddStudent;
use App\Models\Student;
class AllStudentController extends Controller
{

    public function Show_all_student(Request $request)
    {
        // Initialize the query builder for the AddStudent model
        $query = AddStudent::query();

        // Join the users table and select the name column
        $query->join('users', 'add_students.user_id', '=', 'users.id')
            ->select('add_students.*', 'users.name as user_name');

        // Apply filters conditionally
        if ($request->has('date') && !empty($request->date)) {
            $query->where('add_students.dob', 'like', "%{$request->date}%");
        }
        if ($request->has('name') && !empty($request->name)) {
            $query->orWhere('users.name', 'like', "%{$request->name}%");
        }
        if ($request->has('phone') && !empty($request->phone)) {
            $query->orWhere('add_students.phone', 'like', "%{$request->phone}%");
        }
        if ($request->has('category') && !empty($request->category)) {
            $query->orWhere('add_students.category_id', 'like', "%{$request->category}%");
        }

        // Paginate the results
        $students = $query->paginate(10);

        // Return the paginated results
        return $students;
    }

    public function auth_type_student(Request $request)
    {
        // Initialize the query builder for the AddStudent model and filter by student_type
         $query = Student::with('user')->where('student_type', 'auth');

        // Join the users table and select the name column
//        $query->join('users', 'add_students.user_id', '=', 'users.id')
//            ->select('add_students.*', 'users.name as user_name');

        // Apply filters conditionally
        if ($request->filled('date')) {
            $query->where('dob', 'like', "%{$request->date}%");
        }
        if ($request->filled('name')) {
            $query->orWhere('users.name', 'like', "%{$request->name}%");
        }
        if ($request->has('phone') && !empty($request->phone)) {
            $query->orWhere('add_students.phone', 'like', "%{$request->phone}%");
        }
        if ($request->has('category') && !empty($request->category)) {
            $query->orWhere('add_students.category_id', 'like', "%{$request->category}%");
        }

        // Paginate the results
        $students = $query->paginate(10);

        // Return the paginated results
        return $students;
    }

    public function teacher_type_student(Request $request)
    {
        // Initialize the query builder for the AddStudent model and filter by student_type
        $query = AddStudent::where('student_type', 'teacher_type');

        // Join the users table and select the name column
        $query->join('users', 'add_students.user_id', '=', 'users.id')
            ->select('add_students.*', 'users.name as user_name');

        // Apply filters conditionally
        if ($request->has('date') && !empty($request->date)) {
            $query->where('add_students.dob', 'like', "%{$request->date}%");
        }
        if ($request->has('name') && !empty($request->name)) {
            $query->orWhere('users.name', 'like', "%{$request->name}%");
        }
        if ($request->has('phone') && !empty($request->phone)) {
            $query->orWhere('add_students.phone', 'like', "%{$request->phone}%");
        }
        if ($request->has('category') && !empty($request->category)) {
            $query->orWhere('add_students.category_id', 'like', "%{$request->category}%");
        }

        // Paginate the results
        $students = $query->paginate(10);

        // Return the paginated results
        return $students;
    }

    public function student_details($id)
    {
        $students = AddStudent::where('id',$id)->first();
        if($students){
            return response()->json([
                'status'=>'success',
                'data'=>$students
            ]);
        }else{
            return response()->json([
                'status'=>'error',
                'data'=>$students
            ]);
        }
    }

    public function destroy($id)
    {
        $students = AddStudent::where('id',$id)->delete();
        if($students){
            return response()->json([
                'status'=>'success',
                'message'=>'Delete your record successfully',
            ]);
        }else{
            return response()->json([
                'status'=>'error',
                'message'=>'record not found',
            ]);
        }
    }

    public function event_student()
    {
        return 'hello ';
        return $event_student = Student::where('user_type', 'event')->get();
    }

    public function updateStudent(Request $request)
    {
        $admit_student = AddStudent::find($request->id);
        $admit_student->category_id = $request->category_id ?? $admit_student->category_id;
        $admit_student->batch_id = $request->batch_id ?? $admit_student->batch_id;
        $admit_student->user_id = $request->user_id ?? $admit_student->user_id;
        $admit_student->phone = $request->phone ?? $admit_student->phone;
        $admit_student->gender = $request->gender ?? $admit_student->gender;
        $admit_student->riligion = $request->riligion ?? $admit_student->riligion;
        $admit_student->registration_date = $request->registration_date ?? $admit_student->registration_date;
        $admit_student->dob = $request->dob ?? $admit_student->dob;
        $admit_student->blood_group = $request->blood_group ?? $admit_student->blood_group;
        $admit_student->address = $request->address ?? $admit_student->address;
        $admit_student->add_by = $request->add_by ?? $admit_student->add_by;
        $admit_student->student_type = $request->student_type ?? $admit_student->student_type;
        $admit_student->save();
        if($admit_student){
            return response()->json([
                'status'=>'success',
                'data'=>$admit_student,
            ]);
        }else{
            return response()->json([
                'status'=>false,
                'message'=>'Student record not found'
            ]);
        }
    }




}
