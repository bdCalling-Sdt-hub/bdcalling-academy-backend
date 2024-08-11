<?php

namespace App\Http\Controllers\Api\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\SuccessStory;
use Illuminate\Http\Request;

class SuccessStoryController extends Controller
{

    public function index(Request $request)
    {
        $query = SuccessStory::query();
        if (filled($request->type)){
            $query->where('type',$request->type);
        }
         $successStories = $query->paginate(8);

         return response()->json($successStories);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $input = $request->all();
        $type = $request->type;
        // The upload destination - change this to your own
        $uploadDir = storage_path('app/public/upload/video');

        if (!file_exists($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                return response()->json(['ok' => 0, 'info' => "Failed to create $uploadDir"]);
            }
        }

        $fileName = isset($_REQUEST['name']) ? $_REQUEST['name'] : $_FILES['file']['name'];
        $finalFilePath = $uploadDir . DIRECTORY_SEPARATOR . $fileName;

        // Deal with chunks
        $chunk = isset($_REQUEST['chunk']) ? intval($_REQUEST['chunk']) : 0;
        $chunks = isset($_REQUEST['chunks']) ? intval($_REQUEST['chunks']) : 0;
        $tempFilePath = "{$finalFilePath}.part";
        $out = fopen($tempFilePath, $chunk == 0 ? 'wb' : 'ab');

        if ($out) {
            $in = fopen($_FILES['file']['tmp_name'], 'rb');

            if ($in) {
                while ($buff = fread($in, 4096)) {
                    fwrite($out, $buff);
                }
                fclose($in);
            } else {
                return response()->json(['ok' => 0, 'info' => 'Failed to open input stream']);
            }

            fclose($out);
            unlink($_FILES['file']['tmp_name']);
        } else {
            return response()->json(['ok' => 0, 'info' => 'Failed to open output stream']);
        }

        // Check if the file has been completely uploaded
        if (!$chunks || $chunk == $chunks - 1) {
            rename($tempFilePath, $finalFilePath);
            $array = [
                'file' => 'storage/upload/video/' . $fileName,
                'type' => $type
            ];
            SuccessStory::create($array);
        }

        $info = 'Upload OK';
        $ok = 1;

        return response()->json(['ok' => $ok, 'info' => $info], 200);
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
