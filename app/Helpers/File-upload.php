<?php

use Illuminate\Support\Facades\File;

function uploadFile(object $file, string $uploadPath, string $oldFile = null)
{
    // $files it can be array incase of multi files, and it can be object in case of single file

    $fileNameToStore = "";
    $file_path = public_path($oldFile);

    if ($file_path) {
        if (file_exists($oldFile)) {
            unlink($file_path);
        }
    }

    if (gettype($file) == 'object') {
        $fileNameToStore = $file->hashName();
        $path = $file->move($uploadPath, $fileNameToStore);
    }

    return $uploadPath . $fileNameToStore;
}

function deleteFile(string $fileName, string $uploadPath)
{
    $file_path = public_path($fileName);
    if ($file_path) {
        if (file_exists($file_path)) {
            unlink($file_path);
        }
    }
}

function imagePath()
{
    return 'storage/uploads/images/';
}
