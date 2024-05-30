<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\SuccessStory;
use App\Models\AddStudent;
use Illuminate\Http\Request;

class SuccessStoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
         // Initialize the query builder for the AddStudent model and filter by status
         $query = AddStudent::where('status', 'complet')
         ->with(['user', 'batch', 'course']);

        // Apply filters conditionally
        if ($request->filled('date')) {
        $query->where('dob', 'like', "%{$request->date}%");
        }

        // Using where and orWhere properly
        if ($request->filled('id')) {
        $query->where(function($q) use ($request) {
            $q->where('id', 'like', "%{$request->id}%");
        });
        }

        if ($request->filled('phone')) {
        $query->where(function($q) use ($request) {
            $q->where('phone', 'like', "%{$request->phone}%");
        });
        }

        // Paginate the results
        $students = $query->paginate(10);

        // Return the paginated results
        return response()->json($students);
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
    public function store(Request $request)
    {
        // $_REQUEST['name'];
        $input = $request->all();

        // THE UPLOAD DESTINATION - CHANGE THIS TO YOUR OWN

        $filePath = storage_path('app/public/upload/video');

        if (!file_exists($filePath)) {
            if (!mkdir($filePath, 0777, true)) {
                return response()->json(['ok' => 0, 'info' => "Failed to create $filePath"]);
            }
        }

        $fileName = isset($_REQUEST['name']) ? $_REQUEST['name'] : $_FILES['file']['name'];
        $filePath = $filePath . DIRECTORY_SEPARATOR . $fileName;

        // DEAL WITH CHUNKS

        $chunk = isset($_REQUEST['chunk']) ? intval($_REQUEST['chunk']) : 0;
        $chunks = isset($_REQUEST['chunks']) ? intval($_REQUEST['chunks']) : 0;
        $out = fopen("{$filePath}.part", $chunk == 0 ? 'wb' : 'ab');

        if ($out) {
            $in = fopen($_FILES['file']['tmp_name'], 'rb');

            if ($in) {
                while ($buff = fread($in, 4096)) {
                    fwrite($out, $buff);
                }
            } else {
                return response()->json(['ok' => 0, 'info' => 'Failed to open input stream']);
            }

            fclose($in);
            fclose($out);
            unlink($_FILES['file']['tmp_name']);
        }

        // CHECK IF THE FILE HAS BEEN UPLOADED

        if (!$chunks || $chunk == $chunks - 1) {
            rename("{$filePath}.part", $filePath);
            $array = ['file' => $filePath . $fileName];
            SuccessStory::create($array);
        }

        $info = 'Upload OK';
        $ok = 1;

        return response()->json(['ok' => $ok, 'info' => $info],200);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $successStory = SuccessStory::find($id);

        if (!$successStory) {
            return response()->json([
                'status' => 'error',
                'message' => 'Video not found'
            ]);
        }

        // Get the file path from the database
        $filePath = storage_path('app/public/upload/video/' . $successStory->file);

        // Unlink (delete) the file
        if (file_exists($filePath)) {
            unlink($filePath);
        }

        // Delete the database record
        $delete_video = $successStory->delete();

        if ($delete_video) {
            return response()->json([
                'status' => 'success',
                'message' => 'Video deleted successfully'
            ]);
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Video delete failed'
            ]);
        }
    }
}