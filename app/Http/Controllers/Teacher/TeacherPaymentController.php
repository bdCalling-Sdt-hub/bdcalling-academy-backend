<?php

namespace App\Http\Controllers\Teacher;

use App\Http\Controllers\Controller;
use App\Http\Requests\PaymentRequest;
use App\Models\Payment;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Validation\ValidationException;

class TeacherPaymentController extends Controller
{
    public function teacherPayment(PaymentRequest $request)
    {
        try {
            $payment = new Payment();
            $payment->teacher_id = $request->teacher_id;
            $payment->course_module_id = $request->course_module_id;
            $payment->payment_type = $request->payment_type;
            $payment->amount = $request->amount;
            $payment->payment_date = $request->payment_date;
            $payment->reference_by = $request->reference_by;
            $payment->save();
            return response()->json([
                'message' => 'Payment added successfully',
                'payment' => $payment
            ], 201);

        } catch (ValidationException $e) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (ModelNotFoundException $e) {
            return response()->json([
                'message' => 'Model not found',
                'error' => $e->getMessage()
            ], 404);

        } catch (Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function teacherPaymentUpdate(Request $request, string $id)
    {
        try {
            // Find the payment by ID
            $payment = Payment::findOrFail($id);

            // Update the payment attributes
//            $payment->teacher_id = $payment->teacher_id;
//            $payment->course_module_id = $request->course_module_id;
            $payment->payment_type = $request->payment_type;
            $payment->amount = $request->amount;
            $payment->payment_date = $request->payment_date;
            $payment->reference_by = $request->reference_by;
            $payment->update();

            // Return a success response
            return response()->json([
                'message' => 'Payment updated successfully',
                'payment' => $payment
            ], 200);

        } catch (ValidationException $e) {
            // Return validation error response
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $e->errors()
            ], 422);

        } catch (ModelNotFoundException $e) {
            // Return not found error response
            return response()->json([
                'message' => 'Payment not found',
                'error' => $e->getMessage()
            ], 404);

        } catch (Exception $e) {
            // Return generic error response
            return response()->json([
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
