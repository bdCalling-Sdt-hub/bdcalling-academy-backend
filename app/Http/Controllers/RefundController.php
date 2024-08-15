<?php

namespace App\Http\Controllers;

use App\Http\Requests\RefundRequest;
use App\Models\BatchStudent;
use App\Models\IncludeCost;
use App\Models\Refund;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RefundController extends Controller
{
    //

    public function refund(RefundRequest $request)
    {
//        return $request;
        $student_id = $request->student_id;
        $batch_id = $request->batch_id;

        $student = BatchStudent::where('student_id', $student_id)->where('batch_id', $batch_id)->first();

        if (empty($student)) {
            return response()->json(['message' => 'Student Does Not Exist'], 404);
        }

        // Start a transaction
        DB::beginTransaction();

        try {
            // Update student's status
            $student->status = 'refunded';
            $student->save();

            // Store the cost
            $refund = new Refund();
            $refund->batch_id = $request->batch_id;
            $refund->student_id = $request->student_id;
            $refund->refund_amount = $request->refund_amount;
            $refund->refund_by = auth()->user()->name;
            $refund->save();

            // Commit the transaction
            DB::commit();

            return response()->json(['message' => 'Student is refunded successfully'], 200);

        } catch (\Exception $e) {
            // Rollback the transaction if anything goes wrong
            DB::rollBack();
            Log::error('Error in refund: ' . $e->getMessage());

            return response()->json(['message' => 'An error occurred. No action was taken.'], 500);
        }
    }

}
