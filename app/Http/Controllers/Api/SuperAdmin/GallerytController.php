<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GellaryRequest;
use App\Models\Galleryr;
use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;

class GallerytController extends Controller
{

    public function index()
    {
        $gellary_image = Galleryr::orderBy('id', 'desc')->paginate(9);
        if ($gellary_image) {
            return response()->json([
                'status' => 'success',
                'data' => $gellary_image
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'data' => $gellary_image
            ], 402);
        }
    }

    public function create()
    {
        //
    }

    public function store(GellaryRequest $request)
    {
        $gellary_image = new Galleryr();
        if ($request->hasfile('image')) {
            $file = $request->file('image');
            $extenstion = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extenstion;
            $file->move('images/', $filename);
            // Resize the image
            // $resizedImage = Image::make('images/' . $filename)
            //     ->resize(300, 200)  // Adjust the width and height as per your requirement
            //     ->save();
            $gellary_image->image = 'images/' . $filename;
        }
        $gellary_image->save();
        if ($gellary_image) {
            return response()->json([
                'status' => 'success',
                'data' => $gellary_image
            ]);
        } else {
            return response()->json([
                'status' => 'success',
                'message' => 'Internal server error'
            ]);
        }
    }

    public function show(string $id)
    {
        $gellary_image = Galleryr::where('id', $id)->first();
        if ($gellary_image) {
            return response()->json([
                'status' => 'success',
                'data' => $gellary_image
            ], 200);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Data not found'
            ], 402);
        }
    }

    public function edit(string $id) {}

    public function update(Request $request, string $id)
    {
        $gellary_image = Galleryr::find($request->id);

        if (!$gellary_image) {
            return response()->json([
                'status' => 'error',
                'message' => 'Record image not found',
            ], 404);
        }

        $prevImagePath = $gellary_image->image;

        if ($request->hasFile('image')) {
            // If a new image is uploaded, unlink the previous image if it exists
            if ($prevImagePath) {
                // Unlink the previous image
                if (file_exists(public_path($prevImagePath))) {
                    unlink(public_path($prevImagePath));
                }
            }

            $file = $request->file('image');
            $extension = $file->getClientOriginalExtension();
            $filename = time() . '.' . $extension;
            $file->move('images/', $filename);
            $gellary_image->image = 'images/' . $filename;
        } else {
            // If no new image is uploaded, update the previous image data
            if (!$prevImagePath) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'No new image uploaded and no previous image found',
                ], 400);
            }
            // Update the image data with the previous image path
            $gellary_image->image = $prevImagePath;
        }

        $gellary_image->save();

        return response()->json([
            'status' => 'success',
            'data' => $gellary_image
        ]);
    }

    public function destroy(string $id)
    {
        // Find the Galleryr model instance by its ID
        $gellary_image = Galleryr::find($id);

        if (!$gellary_image) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gallery image not found',
            ], 404);
        }

        // Get the path of the image
        $prevImagePath = $gellary_image->image;

        // If the image path exists, unlink the image
        if ($prevImagePath && file_exists(public_path($prevImagePath))) {
            unlink(public_path($prevImagePath));
        }

        // Delete the Galleryr model instance
        $delete = Galleryr::where('id', $id)->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Gallery image deleted successfully',
        ]);
    }
}
