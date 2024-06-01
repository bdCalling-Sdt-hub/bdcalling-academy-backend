<?php

namespace App\Http\Controllers\Api\Student;
use App\Models\Course;
use App\Models\Order;
use App\Models\AddStudent;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quize;

class StudentDashbordController extends Controller
{
    public function counting_student_info()
    {
         $auth = auth()->user()->id;
         $complete_course = AddStudent::where('user_id',$auth)->where('status','complet')->count();
         $payment =  Order::where('user_id',$auth)->sum('amount');
         $course_fee = Order::where('user_id',$auth)->sum('course_fee');
         $due = $course_fee - $payment;

         return response()->json([
            'status'=>'success',
            'complet_course'=>$complete_course,
            'total_payment'=>$payment,
            'total_due'=>$due,
        ], 200);
    }

    public function all_course()
    {
        $userId = auth()->user()->id;
        $orderCourses = Order::where('user_id', $userId)->pluck('course_id');
        $courses = Course::whereIn('id', $orderCourses)->get();
        if($courses){
            return response()->json(['status'=>'success','data'=>$courses], 200);
        }
        return response()->json(['status'=>false,'message'=>'Record not founde'], 402);
              
    }

    public function course_modul_video($id)
    {
        $courses = Course::where('id', $id)->with('course_module.videos')->get();
        if($courses){
            return response()->json(['status'=>'success','data'=>$courses], 200);
        }
        return response()->json(['status'=>false,'data'=>$courses], 402);

    }

    public function show_quize($id)
    {
        return $show_quize_test = Quize::where('course_module_id', $id)->get();
    }

    
}
