<?php



function saveImage($request, $fileType)
{
    $file = $request->file($fileType);
    $imageName = rand() . '.' . $file->getClientOriginalExtension();
    $directory = 'adminAsset/image/';
    $imgUrl = $directory . $imageName;
    $file->move($directory, $imageName);
    return $imgUrl;
}


function removeImage($imagePath)
{
    // Check if the file exists before attempting to delete it
    if (file_exists($imagePath)) {
        unlink($imagePath);
    }
}

function dataResponse($status,$message,$data){
    return response()->json([
        'status' => $status,
        'message' => $message,
        'data' => $data
    ]);
}

function notExistResponse($status,$message,$data = null){
    return response()->json([
        'status' => $status,
        'message' => $message,
        'data' => $data,
    ]);
}
