<?php

use Illuminate\Support\Facades\File;

function uploadFile(object $file, string $uploadPath, string $oldFile = null)
{
    $fileNameToStore = "";
    $file_path = public_path($oldFile);

    if ($file_path) {
        if (file_exists($oldFile)) {
            unlink($file_path);
        }
    }

    if (gettype($file) == 'object') {
        $fileNameToStore = $file->hashName();
        $path = $file->move(public_path($uploadPath), $fileNameToStore);
    }

    return $uploadPath . $fileNameToStore;
}

function deleteFile(string $fileName)
{
    if(strpos($fileName, 'http') !== false ){
        return;
    }

    $file_path = public_path($fileName);
    // dd($file_path);
    if ($file_path) {
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
}

function imagePath(string $name = '')
{
    return 'uploads/images/' . $name;
}
function videoPath(string $name = '')
{
    return 'uploads/videos/' . $name;
}
function noImage()
{
    return 'images/no-image.png';
}


function validateImageUrl($attr){
    if(strpos($attr, 'http') !== false){
        return $attr;
    }
    else if($attr == null){
        return env('FILE_URL') . noImage();
    }
    else{
        return env('FILE_URL') . $attr;
    }
}
function validateVideoUrl($attr){
    if(strpos($attr, 'http') !== false){
        return $attr;
    }
    else if($attr == null){
        return false;
    }
    else{
        return env('FILE_URL') . $attr;
    }
}
