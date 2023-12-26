<?php

namespace App\Traits;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

trait FilesUploadTrait
{
    /**
     * @param UploadedFile $file
     * @param string $folder
     * @param $fileName
     * @return mixed|string
     */
    public function uploadFile(UploadedFile $file, string $folder = 'uploads', $fileName = null): mixed
    {
        try {
            // Check if a file name is provided, else generate a unique name
            $fileName = $fileName ?? uniqid() . '_' . time() . '.' . $file->getClientOriginalExtension();

            // Move the file to the specified folder within the 'public/uploads' directory
            $file->move(public_path('storage/' . $folder), $fileName);

            return "$folder/$fileName";
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return null;
        }
    }


    /**
     * @param string $url
     * @param string $folder
     * @param string|null $fileName
     * @return string|null
     */
    public function uploadImageFromURL(string $url, string $folder = 'uploads', ?string $fileName = null): ?string
    {
        try {
            $fileContent = file_get_contents($url);

            if ($fileContent === false) {
                return null;
            }

            $extension = pathinfo($url, PATHINFO_EXTENSION);

            $fileName = $fileName ?? uniqid() . '_' . time() . '.' . $extension;

            $filePath = $folder . '/' . $fileName;


            Storage::disk('public')->put('storage/'.$filePath, $fileContent);

            return $filePath;

        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            return null;
        }
    }


}
