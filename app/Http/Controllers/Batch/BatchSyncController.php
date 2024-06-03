<?php

namespace App\Http\Controllers\Batch;

use App\Http\Controllers\Controller;
use App\Http\Requests\BatchSyncRequest;
use App\Models\BatchTeacher;
use Illuminate\Http\Request;

class BatchSyncController extends Controller
{
    public function syncBatch(BatchSyncRequest $request)
    {
        $sync_batch = new BatchTeacher();
        $sync_batch->teacher_user_id = json_encode($request->teacher_user_id);
        $sync_batch->course_id = $request->course_id;
        $sync_batch->batch_id = $request->batch_id;
        $sync_batch->start_date = $request->start_date;
        $sync_batch->end_date = $request->end_date;
        $sync_batch->seat_limit = $request->seat_limit;
        $sync_batch->seat_left = $request->seat_left;
        $sync_batch->discount_price = $request->discount_price;
        $sync_batch->image = saveImage($request,'image');
        $sync_batch->save();

        // Attach multiple teachers
        $teacher_ids = explode(',', $request->user_id);
        $sync_batch->teachers()->attach($teacher_ids);

        return response()->json(['message' => 'Batch Sync Successfully']);
    }
}
