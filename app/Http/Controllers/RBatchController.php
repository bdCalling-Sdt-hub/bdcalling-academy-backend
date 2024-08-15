<?php

namespace App\Http\Controllers;

use App\Http\Requests\BatchRequest;
use App\Models\Batch;
use App\Models\BatchTeacher;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RBatchController extends Controller
{
    public function index(Request $request)
    {
        $query = Batch::with('course')->where('batch_id', 'not like', 'Phoenix%');
        if ($request->has('batch_name')) {
            $query->where('batch_name', 'like', '%' . $request->input('batch_name') . '%');
        }

        if ($request->has('no_pagination')) {
            $batches = $query->get();
            return response()->json(['message' => 'Batch List', 'data' => $batches]);
        }
        $batches = $query->paginate(12);
        return response()->json(['message' => 'Batch List', 'data' => $batches]);
    }

    public function store(BatchRequest $request)
    {
        DB::beginTransaction();
        try {
            $course_id = $request->course_id;
            $course = Course::where('id',$course_id)->first();
            $batch_type = $course->course_type;
            if (!$course) {
                return response()->json([
                    'message' => 'Course does not exist',
                ], 404);
            }
            $batch = new Batch();
            $batch->batch_id = $this->createBatch($course_id, $batch_type);
            $batch->course_id = $course_id;
            $batch->batch_name = $request->batch_name ?? null;
            $batch->start_date = $request->start_date;
            $batch->end_date = $request->end_date ;
            $batch->seat_limit = $request->seat_limit ;
            $batch->seat_left = $request->seat_left ;
            $batch->image = saveImage($request,'image');
            $batch->discount_price = $request->discount_price ?? null;
            $batch->save();
            $teacher_ids = json_decode($request->teacher_id,true);

            foreach ($teacher_ids as $index => $teacher_id) {
                $teacher_batch = new BatchTeacher();
                $teacher_batch->batch_id = $batch->id;
                $teacher_batch->teacher_id = $teacher_id;
            }
            DB::commit();

            return response()->json([
                'message' => 'Batch created successfully',
                'data' => $batch,
            ], 201);

        } catch (\Exception $e) {
            Log::error('Error creating batch: ' . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred while creating the batch',
            ], 500);
        }
    }

    private function createBatch($course_id, $batch_type)
    {
        try {
            $course = Course::findOrFail($course_id);
            $course_count = Batch::where('course_id', $course_id)->count();
            $count_formatted = str_pad($course_count + 1, 2, '0', STR_PAD_LEFT);
            $academy_name = 'BCA';
            $course_type = strtoupper($batch_type);
            $course_type_filter = ($course_type === 'ONLINE') ? 'O' : '';
            $course_name_filter = strtoupper(substr($course->course_name, 0, 3));
            $year = date('y');
            $batch_id = $academy_name . '-' . $course_type_filter . $course_name_filter . '-' . $year . $count_formatted;

            return $batch_id;
        } catch (\Exception $e) {
            Log::error('Error creating batch ID: ' . $e->getMessage());

            throw new \Exception('Error generating batch ID');
        }
    }

    public function show(string $id)
    {
        try {
            $batch = Batch::with('course','teachers')->find($id);
            if (!$batch) {
                return response()->json([
                    'message' => 'Batch not found',
                ], 404);
            }

            return response()->json([
                'message' => 'Batch retrieved successfully',
                'data' => $batch,
            ], 200);

        } catch (\Exception $e) {
            // Log the error for debugging purposes
            Log::error('Error fetching batch: ' . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred while fetching the batch',
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            $batch = Batch::find($id);

            if (!$batch) {
                return response()->json([
                    'message' => 'Batch not found',
                ], 404);
            }

            //$batch->batch_id = $this->createBatch($course_id, $batch_type);
            //$batch->course_id = $course_id;
            $batch->batch_name = $request->batch_name ?? $batch->batch_name;
            $batch->start_date = $request->start_date ?? $batch->start_date;
            $batch->end_date = $request->end_date ?? $batch->end_date;
            $batch->seat_limit = $request->seat_limit ?? $batch->seat_limit;
            $batch->seat_left = $request->seat_left ?? $batch->seat_left;
            $batch->discount_price = $request->discount_price ?? $batch->discount_price;
            if ($request->file('image')) {
                if (!empty($batch->image)) {
                    removeImage($batch->image);
                }
                $batch->image = saveImage($request,'image');
            }
            $batch->discount_price = $request->discount_price ?? $batch->discount_price;
            $batch->update();

            $teacher_ids = json_decode($request->teacher_user_ids, true);

            if (is_array($teacher_ids)) {
                BatchTeacher::where('batch_id', $batch->id)->delete();

                foreach ($teacher_ids as $index => $teacher_id) {
                    $teacher_batch = new BatchTeacher();
                    $teacher_batch->batch_id = $batch->id;
                    $teacher_batch->teacher_id = $teacher_id;
                    $teacher_batch->save();
                }
            }

            DB::commit();

            return response()->json([
                'message' => 'Batch updated successfully',
                'data' => $batch,
            ], 200);

        } catch (\Exception $e) {
            Log::error('Error updating batch: ' . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred while updating the batch',
            ], 500);
        }
    }

    public function destroy(string $id)
    {
        try {
            // Validate that the batch exists
            $batch = Batch::find($id);
            if (!$batch) {
                return response()->json([
                    'message' => 'Batch not found',
                ], 404);
            }

            // Delete the batch
            $batch->delete();

            return response()->json([
                'message' => 'Batch deleted successfully',
            ], 200);

        } catch (\Exception $e) {
            // Log the error for debugging purposes
            Log::error('Error deleting batch: ' . $e->getMessage());

            return response()->json([
                'message' => 'An error occurred while deleting the batch',
            ], 500);
        }
    }

    public function teacherBatch()
    {
        $teacher_id = auth()->user()->teacher->id;
        if (!$teacher_id){
            return response()->json(['message' => 'Unauthorized Teacher'], 404);
        }
        $batch = BatchTeacher::where('teacher_id',$teacher_id)->get();
        $filter_batch = $batch->map(function ($item){
            return $item->batch;
        });
        return response()->json($filter_batch);

    }
}
