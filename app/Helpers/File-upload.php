<?php

use Illuminate\Support\Facades\File;

function uploadFile(object $file, string $uploadPath, string $oldFile = null)
{
    $fileNameToStore = "";
    $file_path = public_path($oldFile);

    if ($file_path) {
        if (file_exists($oldFile) && strpos($oldFile, 'uploads/') !== false) {

            unlink($file_path);
        }
    }

    if (gettype($file) == 'object') {
        $fileNameToStore = $file->hashName();
        $path = $file->move(public_path($uploadPath), $fileNameToStore);
    }

    return $uploadPath . $fileNameToStore;
}



function uploadBase64($base64_string, $output_file) {
    $img = str_replace('data:image/png;base64,', '', $base64_string);
    $img = str_replace(' ', '+', $img);
    $data = base64_decode($img);
    file_put_contents($output_file, $data);
}


function deleteFile(string $fileName)
{
    if (strpos($fileName, 'http') !== false) {
        return;
    }

    $file_path = public_path($fileName);
    // dd($file_path);
    if ($file_path && strpos($file_path, 'uploads/')  !== false) {
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


function validateImageUrl($attr)
{
    if (strpos($attr, 'http') !== false) {
        return $attr;
    } else if ($attr == null) {
        return env('FILE_URL') . noImage();
    } else {
        return env('FILE_URL') . $attr;
    }
}
function validateVideoUrl($attr)
{
    if (strpos($attr, 'http') !== false) {
        return $attr;
    } else if ($attr == null) {
        return false;
    } else {
        return env('FILE_URL') . $attr;
    }
}
