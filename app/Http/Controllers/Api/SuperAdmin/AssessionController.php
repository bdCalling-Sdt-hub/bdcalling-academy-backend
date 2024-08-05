<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AssessionRequest;
use App\Models\Assession;
use Illuminate\Http\Request;

class AssessionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Retrieve all Assessions from the database
        $assessions = Assession::orderBy('id', 'desc')->get();
        if ($assessions) {
            return response()->json(['data' => $assessions], 201);
        } else {
            return response()->json(['message' => 'Record not found', 'data' => $assessions], 402);
        }

        // Return a JSON response with the Assessions data
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
    public function store(AssessionRequest $request)
    {
        // Create a new Assession instance
        $assession = new Assession();
        $assession->title = $request->title;
        // Save the image file and store its path
//        $imagePath = $request->file('image')->store('images');
//        $assession->image = $imagePath;

        if ($request->file('image')) {
            if (!empty($assession->image)) {
                removeImage($assession->image);
            }
            $assession->image = saveImage($request, 'image');
        }
        $assession->save();

        // Return a response indicating success
        return response()->json(['message' => 'Assession created successfully', 'assession' => $assession], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $assessions = Assession::where('id', $id)->first();
        if ($assessions) {
            return response()->json(['data' => $assessions], 201);
        } else {
            return response()->json(['message' => 'Record not found'], 402);
        }
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
        $assession_image = Assession::find($request->id);

        if (!$assession_image) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record image not found',
            ], 404);
        }

        if ($request->file('image')) {
            if (!empty($teacher->image)) {
                removeImage($teacher->image);
            }
            $teacher->image = saveImage($request, 'image');
        }

        $assession_image->save();

        return response()->json([
            'status' => 'success',
            'data' => $assession_image
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        // Find the Galleryr model instance by its ID
        $assession = Assession::find($id);

        if (!$assession) {
            return response()->json([
                'status' => 'error',
                'message' => 'Assesion image not found',
            ], 404);
        }

        // Get the path of the image
        $prevImagePath = $assession->image;

        // If the image path exists, unlink the image
        if ($prevImagePath && file_exists(public_path($prevImagePath))) {
            unlink(public_path($prevImagePath));
        }

        // Delete the Galleryr model instance
        $delete = Assession::where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Assession image deleted successfully',
        ]);
    }
}
