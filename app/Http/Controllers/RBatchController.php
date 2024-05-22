<?php

namespace App\Http\Controllers;

use App\Models\Batch;
use Illuminate\Http\Request;

class RBatchController extends Controller
{

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function batchId()
    {

    }

    private function createBatch($course_id)
    {
        $course_count = Batch::where('id', $course_id)->count();
        $count_formatted = str_pad($course_count, 2, '0', STR_PAD_LEFT);
        $academy_name = 'BCA';
        $course_type = 'Online';
        $course_name = 'php and laravel';
        $course_type = strtoupper($course_type);
        $course_type_filter = ($course_type === 'ONLINE') ? 'O' : '';
        $course_name_filter = strtoupper(substr($course_name, 0, 3));
        $year = date('y');
        $batch_id = $academy_name . '-' . $course_type_filter . $course_name_filter . '-' . $year . $count_formatted;
        return $batch_id;
    }

    public function store(Request $request)
    {
        //
        $course_id = $request->course_id;
        $batch = new Batch();
        $batch->course_id = $request->course_id;
        $batch->trainer_id = $request->trainer_id ?? null;
        $batch->coupon_id = $request->coupon_id ?? null;
        $batch->batch_name = $request->batch_name ?? null;
        $batch->start_time = $request->start_time;
        $batch->end_time = $request->end_time;
        $batch->total_seat = $request->total_seat;
        $batch->seat_left = $request->seat_left ?? null;
        $batch->discount = $request->discount ?? null;

        $batch->batch_id = $this->createBatch($course_id);
        $batch->save();
        return response()->json([
            'message' => 'Batch is created successfully',
            'data' => $batch,
        ]);
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
