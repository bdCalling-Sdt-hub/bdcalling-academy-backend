<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Http\Requests\AdmitPaymentRequest;
use App\Models\Batch;
use App\Models\Order;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentPaymentController extends Controller
{
    public function admittedPayment(AdmitPaymentRequest $request)
    {
        $student_info = Student::find($request->student_id);
        if (empty($student_info)) {
            return response()->json(['message' => 'Student does not exist'], 404);
        }

        $student = $request->student_id;
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
        $check_amount = Order::where('student_id', $student)
//            ->where('course_id', $course)
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

        if ($due > 0 || $request->payment_type == 'one_time') {
            // Create a new order
            $newOrder = new Order();
//        $newOrder->user_id = $user;
            $newOrder->student_id = $request->student_id;
            $newOrder->batch_id = $batch;
//        $newOrder->course_id = $course;
            $newOrder->course_fee = $course_fee;
            $newOrder->price = $price;
            $newOrder->gateway_name = $request->gateway_name;
            $newOrder->amount = $amount;
            $newOrder->currency = $request->currency;
            $newOrder->discount_price = $discount_price;
            $newOrder->discount_reference = $request->discount_reference;
            $newOrder->due = $due;
            $newOrder->status = $paid;
            $newOrder->transaction_id = $request->transaction_id;
            $newOrder->installment_date = $request->installment_date;
            $newOrder->payment_type = $request->payment_type;
            $newOrder->save();

            $update_student = Student::find($request->student_id);
            $update_student->status = 'enrolled';
            $update_student->save();
            return response()->json(['status'=>'success', 'message'=>'Payment successfully complete','data' => $newOrder]);
        }
        else{
            return response()->json(['message' => 'Payment for this batch has already been processed'],409);
        }

    }

    public function showSingleStudentPaymentHistory(Request $request)
    {
        $student_id = $request->student_id;
        $batch_id = $request->batch_id;
        $order_details = Order::with('student.user','batch.course')->where('student_id', $student_id)->where('batch_id',$batch_id)->get();

        $formatted_student_payment_details = $order_details->map(function ($order){
//            $order->installment_date = $order->installment_date;
            return $order;
        });
        return response()->json(['data' => $formatted_student_payment_details]);
    }
}
