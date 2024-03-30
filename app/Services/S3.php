<?php

namespace App\Services;

use Illuminate\Support\Facades\Storage;

class S3
{

    public static function saveToS3($file, $currentRecord, $oldFile = null)
    {

        // for edit if file did not changed return same path
        if ($file === $oldFile) {
            return $oldFile;
        }

        // for edit if the file changed remove the old one from S3
        if($oldFile){
            if (Storage::disk('s3')->exists($oldFile)) {
                Storage::disk('s3')->delete($oldFile);
            }
        }

        // for create and edit -> try to save file to s3 then return path
        $storeFolder = 'Attachments/' . class_basename($currentRecord) . '/' . $currentRecord->id;
        $path = $file->storePublicly($storeFolder, 's3');

        try {
            Storage::disk('s3')->exists($path);
            return $path;
        } catch (\Throwable $th) {
            return null;
        }

    }
}
