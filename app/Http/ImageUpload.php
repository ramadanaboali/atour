<?php

use Illuminate\Support\Facades\Storage;

if (! function_exists('upload')) {
    function upload($file, $disk = 'public', $folder = '', $filename = '')
    {
        $type = [
            'jpg' => 'image',
            'jpeg' => 'image',
            'png' => 'image',
            'svg' => 'image',
            'webp' => 'image',
            'gif' => 'image',
            'mp4' => 'video',
            'mpg' => 'video',
            'mpeg' => 'video',
            'webm' => 'video',
            'ogg' => 'video',
            'avi' => 'video',
            'mov' => 'video',
            'flv' => 'video',
            'swf' => 'video',
            'mkv' => 'video',
            'wmv' => 'video',
            'wma' => 'audio',
            'aac' => 'audio',
            'wav' => 'audio',
            'mp3' => 'audio',
            'zip' => 'archive',
            'rar' => 'archive',
            '7z' => 'archive',
            'doc' => 'document',
            'txt' => 'document',
            'docx' => 'document',
            'pdf' => 'document',
            'csv' => 'document',
            'xml' => 'document',
            'ods' => 'document',
            'xlr' => 'document',
            'xls' => 'document',
            'xlsx' => 'document',
            'ppt' => 'document',
            'odt' => 'document',
            'odp' => 'document',
        ];
        if (isset($file)) {
            $extension = strtolower($file->getClientOriginalExtension());
            if (isset($type[$extension])) {
                if ($disk == 's3') {
                    $filePath = '/'.$folder;
                    return $spaceImage = Storage::disk($disk)->put($filePath, $file);
//                    $spaceImage = Storage::disk($disk)->url($spaceImage);
                } elseif ($disk == 'public') {
                    $filename = ! empty($filename) ? $filename : rand().'.'. time() . '.' . $file->getClientOriginalExtension();

                    $spaceImage=Storage::disk($disk)->putFileAs(
                        $folder,
                        $file,
                        $filename
                    );
                }
                return $spaceImage;
            }
        }
        return null;
    }
}
