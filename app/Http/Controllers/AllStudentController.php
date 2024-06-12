<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AddStudent;
use App\Models\Student;
class AllStudentController extends Controller
{

    public function Show_all_student(Request $request)
    {

        $registration_date = $request->get('registration_date');
        $name = $request->get('name');
        $phone_number = $request->get('phone_number');
        $category_name = $request->get('category_name');
        //$batch_id = $request->get('batch_id');
    
        $query = Student::with(['user', 'category']);
    
        if (!empty($registration_date)) {
            $query->where('registration_date', $registration_date);

        }
    
        if (!empty($name)) {
            $query->whereHas('user', function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            });
        }
    
        if (!empty($phone_number)) {
            $query->where('phone_number', $phone_number);
        }
    
        if (!empty($category_name)) {
            $query->whereHas('category', function ($query) use ($category_name) {
                $query->where('category_id', 'like', '%' . $category_name . '%');
            });
        }

    
        // if (!empty($batch_id)) {
        //     $query->where('batch_id', 'like', '%' . $batch_id . '%');
        // }
    
        return $query->paginate(10);

    }

    public function auth_type_student(Request $request)
    {

        $registration_date = $request->get('registration_date');
        $name = $request->get('name');
        $phone_number = $request->get('phone_number');
       
    
        $query = Student::where('student_type','auth student')->with(['user', 'category']);
    
        if (!empty($registration_date)) {
            $query->where('registration_date', $registration_date);

        }
    
        if (!empty($name)) {
            $query->whereHas('user', function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            });
        }
    
        if (!empty($phone_number)) {
            $query->where('phone_number', $phone_number);
        }
    
        return $query->paginate(10);

    }

    public function teacher_type_student(Request $request)
    {
        $registration_date = $request->get('registration_date');
        $name = $request->get('name');
        $phone_number = $request->get('phone_number');
        $category_name = $request->get('category_name');
       
    
        $query = Student::where('student_type','techer student')->with(['user', 'category']);
    
        if (!empty($registration_date)) {
            $query->where('registration_date', $registration_date);
        }
    
        if (!empty($name)) {
            $query->whereHas('user', function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            });
        }
    
        if (!empty($phone_number)) {
            $query->where('phone_number', $phone_number);
        }
        
    
        return $query->paginate(10);
    }

    public function event_type_student(Request $request)
    {
        $registration_date = $request->get('registration_date');
        $name = $request->get('name');
        $phone_number = $request->get('phone_number');
        $category_name = $request->get('category_name');
        
    
        $query = Student::where('student_type','event student')->with(['user', 'category']);
    
        if (!empty($registration_date)) {
            $query->where('registration_date', $registration_date);
        }
    
        if (!empty($name)) {
            $query->whereHas('user', function ($query) use ($name) {
                $query->where('name', 'like', '%' . $name . '%');
            });
        }
    
        if (!empty($phone_number)) {
            $query->where('phone_number', $phone_number);
        }

        if (!empty($category_name)) {
            $query->whereHas('category', function ($query) use ($category_name) {
                $query->where('category_id', 'like', '%' . $category_name . '%');
            });
        }
    
        return $query->paginate(10);
    }
    

    public function student_details($id)
    {
        $students = Student::where('id',$id)->with(['user', 'category'])->first();
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
        $students = Student::where('id',$id)->delete();
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
