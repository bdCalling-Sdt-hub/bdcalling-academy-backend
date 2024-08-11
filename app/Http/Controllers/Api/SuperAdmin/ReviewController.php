<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ReviewRequest;
use App\Models\Review;
use Validator;
class ReviewController extends Controller
{

    public function index()
    {
        $student_id = auth()->user()->student->id;
        $reviews = Review::with('student.user')->where('student_id',$student_id)->paginate(9);
        return response()->json($reviews);
    }

    public function create()
    {
        //
    }

    public function store(ReviewRequest $request)
    {
        $batch_id = $request->batch_id;
        $student_id = auth()->user()->student->id;
        $exist_review = Review::where('student_id',$student_id)->where('batch_id',$batch_id)->first();
        if($exist_review){

            return response()->json(['message' => 'Review already exist'],403);
        }
        $review = new Review();
        $review->student_id = auth()->user()->student->id;
        $review->course_id = $request->course_id;
        $review->batch_id = $request->batch_id;
        $review->rating_value = $request->rating_value;
        $review->message = $request->message;
        $review->save();
        return response()->json(['message' => 'Review created successfully'],201);
    }

    public function show(string $id)
    {
        return Review::findOrFail($id);
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        // Find the review by ID
        $review = Review::find($id);

        // Check if the review exists
        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }

        // Update fields only if the request value is not null
        $review->student_id = $request->filled('student_id') ? $request->student_id : $review->student_id;
        $review->course_id = $request->filled('course_id') ? $request->course_id : $review->course_id;
        $review->batch_id = $request->filled('batch_id') ? $request->batch_id : $review->batch_id;
        $review->rating_value = $request->filled('rating_value') ? $request->rating_value : $review->rating_value;
        $review->message = $request->filled('message') ? $request->message : $review->message;

        $review->save();

        return response()->json($review);
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $review = Review::findOrFail($id);
        $review->delete();

        return response()->json(['message'=>'Review delete successfully']);
    }

    public function allReviews(Request $request)
    {
        $reviews = Review::with('student.user')->paginate(9);
        return response()->json($reviews);
    }
}
