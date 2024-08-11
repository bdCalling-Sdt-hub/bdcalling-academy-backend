<?php
namespace App\Http\Controllers;

use App\Models\TrainerReview;
use Illuminate\Http\Request;

class TrainerReviewController extends Controller
{
    // Index: List all reviews
    public function index()
    {
        $reviews = TrainerReview::with(['user.student','teacher.user'])->paginate(8);
        return response()->json($reviews, 200);
    }

    // Store: Create a new review
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validatedData = $request->validate([
            'user_id' => 'exists:users,id',
            'teacher_id' => 'required|exists:teachers,id',
            'batch_id' => 'required|exists:batches,id',
            'rating_value' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        // Create the review
        $review = new TrainerReview();
        $review->user_id = auth()->user()->id;
        $review->teacher_id = $request->teacher_id;
        $review->batch_id = $request->batch_id;
        $review->rating_value = $request->rating_value;
        $review->review = $request->review;
        $review->save();

        return response()->json(['message' => 'Review created successfully', 'review' => $review], 201);
    }

    // Update: Update an existing review
    public function update(Request $request, $id)
    {
        // Find the review by ID
        $review = TrainerReview::findOrFail($id);

        // Validate the incoming request data
        $validatedData = $request->validate([
            'user_id' => 'sometimes|required|exists:users,id',
            'teacher_id' => 'sometimes|required|exists:teachers,id',
            'batch_id' => 'sometimes|required|exists:batches,id',
            'rating_value' => 'sometimes|required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        // Update the review
        $review->update($validatedData);

        return response()->json(['message' => 'Review updated successfully', 'review' => $review], 200);
    }

    // Destroy: Delete a review
    public function destroy($id)
    {
        // Find the review by ID
        $review = TrainerReview::findOrFail($id);

        // Delete the review
        $review->delete();

        return response()->json(['message' => 'Review deleted successfully'], 200);
    }
    public function publishTrainerReview(Request $request, $id)
    {
        // Find the review by ID
        $review = TrainerReview::findOrFail($id);

        // Toggle the status
        $review->status = $review->status === 'published' ? 'unpublished' : 'published';
        $review->save();

        $statusMessage = $review->status === 'published' ? 'Review published successfully' : 'Review unpublished successfully';

        return response()->json(['message' => $statusMessage, 'review' => $review], 200);
    }
}

