<?php

namespace App\Services\General;


use Illuminate\Support\Facades\Storage;

class StorageService
{
    protected string $disk;

    public function __construct()
    {
        $this->disk='public';
    }

    public function storeFile($file,$path,$i=0): bool|string|null
    {
        $filename = time().$i.'.' . $file->getClientOriginalExtension();
        return upload($file, $this->disk, $path, $filename);
    }

    public function getFile($path)
    {
        return Storage::disk($this->disk)->url($path);
    }

    public function deleteFile($path): bool
    {
        return Storage::disk($this->disk)->delete($path);
    }
}


