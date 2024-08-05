<?php

use App\Models\Order;
use App\Models\Student;
use Illuminate\Http\Request;
class PaymentService
{
    public function payment(Request $request)
    {
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
}
