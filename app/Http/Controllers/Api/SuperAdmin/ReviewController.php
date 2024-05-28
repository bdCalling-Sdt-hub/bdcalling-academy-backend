<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\ReviewRequest;
use App\Models\Review;
use Validator;
class ReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reviews = Review::all();
        return response()->json($reviews);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(ReviewRequest $request)
    {
        return Review::create($request->all());
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        return Review::findOrFail($id);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        // Find the review by ID
        $review = Review::find($id);
    
        // Check if the review exists
        if (!$review) {
            return response()->json(['message' => 'Review not found'], 404);
        }
    
        // Update fields only if the request value is not null
        $review->add_students_id = $request->filled('add_students_id') ? $request->add_students_id : $review->add_students_id;
        $review->course_id = $request->filled('course_id') ? $request->course_id : $review->course_id;
        $review->batch_id = $request->filled('batch_id') ? $request->batch_id : $review->batch_id;
        $review->rating_value = $request->filled('rating_value') ? $request->rating_value : $review->rating_value;
        $review->message = $request->filled('message') ? $request->message : $review->message;
    
        // Save the updated review
        $review->save();
    
        // Return the updated review
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
}
