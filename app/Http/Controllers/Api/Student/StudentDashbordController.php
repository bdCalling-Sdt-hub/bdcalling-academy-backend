<?php

namespace App\Http\Controllers\Api\Student;
use App\Models\Course;
use App\Models\Order;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StudentDashbordController extends Controller
{
    public function all_course()
    {
        $user =  auth()->user()->id;
         $order = Order::where('user_id', $user)->get('course_id');
         $course_data = [];
        foreach($order as $courses){
            $couser_all = Course::whereIn('id', $order)->get();
            $course_data[] = $couser_all;
        }
        
        return $course_data;
      //return  $course = Course::with('course_module.videos')->get();

    }
}
