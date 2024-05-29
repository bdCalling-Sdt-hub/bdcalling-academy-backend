<?php

namespace App\Http\Controllers;

use App\Http\Requests\BatchRequest;
use App\Models\Batch;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RBatchController extends Controller
{
    public function index(Request $request)
    {
        $query = Batch::query();
        if ($request->has('batch_name')) {
            $query->where('batch_name', 'like', '%' . $request->input('batch_name') . '%');
        }
        $batches = $query->paginate(9);
        return response()->json(['message' => 'Batch List', 'data' => $batches]);
    }

    public function store(BatchRequest $request)
    {
        try {
            $course_id = $request->course_id;
            $batch_type = $request->batch_type;

            $course = Course::find($course_id);
            if (!$course) {
                return response()->json([
                    'message' => 'Course does not exist',
                ], 404);
            }

            $batch = new Batch();
            $batch->batch_id = $this->createBatch($course_id, $batch_type);
            $batch->course_id = $course_id;
            $batch->batch_name = $request->batch_name ?? null;
            $batch->batch_type = $batch_type;
            $batch->save();

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
            $batch = Batch::find($id);
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

    public function update(BatchRequest $request, $id)
    {
        try {
            $course_id = $request->course_id;
            $course = Course::find($course_id);
            if (!$course) {
                return response()->json([
                    'message' => 'Course does not exist',
                ], 404);
            }

            $batch = Batch::find($id);
            if (!$batch) {
                return response()->json([
                    'message' => 'Batch not found',
                ], 404);
            }

            // Update batch details
            $batch_type = $request->batch_type;
            $batch->batch_id = $this->createBatch($course_id, $batch_type);
            $batch->course_id = $course_id;
            $batch->batch_name = $request->batch_name;
            $batch->batch_type = $batch_type;
            $batch->save();

            return response()->json([
                'message' => 'Batch updated successfully',
                'data' => $batch,
            ], 200);

        } catch (\Exception $e) {
            // Log the error for debugging purposes
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
}
