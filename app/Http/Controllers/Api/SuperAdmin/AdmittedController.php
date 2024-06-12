<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\AddStudent;
use App\Models\Order;
class AdmittedController extends Controller
{
    // public function admittedStudent(Request $request)
    // {
    //     // Initialize the query builder for the AddStudent model and filter by student_type
    //     $query = AddStudent::where('status', 'enrolled')
    //                 ->with(['user', 'batch', 'course', 'orders']);

    //     // Apply filters conditionally
    //     if ($request->filled('date')) {
    //         $query->where('dob', 'like', "%{$request->date}%");
    //     }
    //     if ($request->filled('id')) {
    //         $query->orWhere('id', 'like', "%{$request->student_id}%");
    //     }
    //     if ($request->filled('batch_no')) {
    //         $query->orWhere('batch_id', 'like', "%{$request->batch_id}%");
    //     }
    //     if ($request->filled('phone')) {
    //         $query->orWhere('phone', 'like', "%{$request->phone}%");
    //     }
    //     if ($request->filled('course_id')) {
    //         $query->orWhere('course_id', 'like', "%{$request->course_id}%");
    //     }

    //     // Paginate the results
    //     $students = $query->paginate(10);

    //     // Return the paginated results
    //     return response()->json($students);
    // }
    public function admittedStudent(Request $request)
    {
        // Initialize the query builder for the AddStudent model and filter by status
        $query = AddStudent::where('status', 'enrolled')
                    ->with(['user', 'batch', 'course', 'orders']);

        // Apply filters conditionally
        if ($request->filled('date')) {
            $query->where('dob', 'like', "%{$request->date}%");
        }

        // Using where and orWhere properly
        if ($request->filled('id')) {
            $query->where(function($q) use ($request) {
                $q->where('id', 'like', "%{$request->id}%");
            });
        }

        if ($request->filled('batch_no')) {
            $query->where(function($q) use ($request) {
                $q->where('batch_id', 'like', "%{$request->batch_no}%");
            });
        }

        if ($request->filled('phone')) {
            $query->where(function($q) use ($request) {
                $q->where('phone', 'like', "%{$request->phone}%");
            });
        }

        if ($request->filled('course_id')) {
            $query->where(function($q) use ($request) {
                $q->where('course_id', 'like', "%{$request->course_id}%");
            });
        }

        // Paginate the results
        $students = $query->paginate(10);

        // Return the paginated results
        return response()->json($students);
    }



    public function admittedPayment(Request $request)
    {
        $user = $request->user_id;
        $course = $request->course_id;
        $batch = $request->batch_id;

        $course_fee = $request->course_fee;
        $discount_price = $request->discount_price;
        $amount = $request->amount;

        $total_amount = 0;
        $paid = 'due'; // Default status
        $due = $course_fee; // Initialize due as the full course fee

        if ($discount_price) {
            $price = $course_fee - $discount_price;
        } else {
            $price = $course_fee;
        }

        // Check the total amount paid so far for this user, course, and batch
        $check_amount = Order::where('user_id', $user)
            ->where('course_id', $course)
            ->where('batch_id', $batch)
            ->sum('amount');

        // Add the current payment amount to the total amount
        $total_amount = $check_amount + $amount;

        // Determine if the course fee has been fully paid
        if ($total_amount >= $price) {
            $paid = 'paid';
            $due = 0; // No due amount since it's fully paid
        } else {
            $due = $price - $total_amount;
        }

        // Create a new order
        $newOrder = new Order();
        $newOrder->user_id = $user;
        $newOrder->student_id = $request->student_id;
        $newOrder->batch_id = $batch;
        $newOrder->course_id = $course;
        $newOrder->course_fee = $course_fee;
        $newOrder->price = $price;
        $newOrder->gateway_name = $request->gateway_name;
        $newOrder->amount = $amount;
        $newOrder->currency = $request->currency;
        $newOrder->discount_price = $discount_price;
        $newOrder->discount_referance = $request->discount_referance;
        $newOrder->due = $due;
        $newOrder->status = $paid;
        $newOrder->transaction_id = $request->transetion;
        $newOrder->save();

        $update_student = Student::find($request->student_id);
        $update_student->status = 'enrolled';
        $update_student->save();
        return response()->json(['status'=>'success', 'message'=>'Payment successfully complete']);
    }

    public function singel_admitted_student($id)
    {
        $singel_admitted_std = AddStudent::where('id',$id)
        ->where('status', 'enrolled')
        ->with(['user', 'batch', 'course', 'orders'])
        ->first();
        if($singel_admitted_std){
            return response()->json([
                'status'=>'success',
                'data'=>$singel_admitted_std
            ],200);
        }else{
            return response()->json([
                'status'=>'error',
                'message'=>'Record not found',
            ],401);
        }

    }

    public function dropout_student(Request $request)
    {
        $update_student = Student::find($request->add_student_id);
        $update_student->status = 'Dropout';
        $update_student->save();
        if($update_student)
        {
            return response()->json([
                'status'=>'success',
                'message'=>'Dropout student successfully',
            ],200);
        }else{
            return response()->json([
                'status'=>'error',
                'message'=>'Record not found',
            ],200);
        }
    }

}
