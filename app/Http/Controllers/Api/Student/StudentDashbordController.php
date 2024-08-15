<?php

namespace App\Http\Controllers\Api\Student;
use App\Models\BatchStudent;
use App\Models\Course;
use App\Models\Order;
use App\Models\Student;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Quize;
use App\Models\Anseware;
use App\Models\Attendance;
use App\Models\Batch;
class StudentDashbordController extends Controller
{
    public function counting_student_info()
    {
         $auth = auth()->user()->id;
         $check_student = Student::where('user_id', $auth)->pluck('id');
         $complete_class = Attendance::whereIn('student_id', $check_student)->count();
         $complete_course = BatchStudent::where('student_id',$check_student)->where('status','complete')->count();
         $payment =  Order::where('student_id',$auth)->sum('amount');
         $course_fee = Order::where('student_id',$auth)->sum('course_fee');
         $due = $course_fee - $payment;

         return response()->json([
            'status'=>'success',
            'complete_course'=>$complete_course,
            'total_payment'=>$payment,
            'total_due'=>$due,
            'complete_class'=>$complete_class,
        ], 200);
    }

    public function all_course()
    {
        $userId = auth()->user()->id;
        $student_check = Student::where('user_id', $userId)->first();
        $student_id = $student_check->id;

        $orderCourses = Order::where('student_id', $student_id)->pluck('batch_id');
        $courses = Batch::whereIn('id', $orderCourses)->with('course')->get();
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
         $show_quize_test = Quize::where('course_module_id', $id)->get();
         if($show_quize_test){
            return response()->json(['status'=>'success','data'=>$show_quize_test],200);
         }else{
            return response()->json(['status'=>false,'message'=>'Record not found'],400);
         }
    }

    public function exam_test_ans(Request $request)
    {
        $auth = auth()->user()->id;
        $course_module_id = $request->course_module_id;
        $check = Anseware::where('user_id', $auth)->where('course_module_id', $course_module_id)->first();
        if(! $check){
            $exam = new Anseware();
            $exam->user_id = $auth;
            $exam->course_module_id = $request->course_module_id;
            $exam->mark = $request->mark;
            $exam->status= $request->status;
            $exam->save();
            if($exam){
                return response()->json(['status'=>'success','data'=>$exam],200);
            }else{
                return response()->json(['status'=>false,'message'=>'Internall server error'],400);
            }
        }else{
            return response()->json(['status'=>false,'message'=>'All ready examination complet'],400);
        }

    }


}
