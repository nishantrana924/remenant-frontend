<?php

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

if (!function_exists('reduceImageSize')) {
    /**
     * Global function to reduce image size and save to storage.
     * It uses GD library to resize and compress.
     * Falls back to standard storage if GD is missing.
     */
    function reduceImageSize($file, $folder = 'uploads', $maxWidth = 1200, $quality = 75)
    {
        // 1. Basic fallback if GD is not available
        if (!extension_loaded('gd')) {
            return $file->store($folder, 'public');
        }

        try {
            $imageInfo = getimagesize($file);
            if (!$imageInfo) {
                return $file->store($folder, 'public');
            }

            $width = $imageInfo[0];
            $height = $imageInfo[1];
            $mime = $imageInfo['mime'];

            // 2. Calculate New Dimensions
            $newWidth = $width;
            $newHeight = $height;

            if ($width > $maxWidth) {
                $newWidth = $maxWidth;
                $newHeight = floor(($height / $width) * $newWidth);
            }

            // 3. Create image resource based on type
            switch ($mime) {
                case 'image/jpeg':
                case 'image/jpg':
                    $src = imagecreatefromjpeg($file);
                    break;
                case 'image/png':
                    $src = imagecreatefrompng($file);
                    break;
                case 'image/webp':
                    $src = imagecreatefromwebp($file);
                    break;
                default:
                    return $file->store($folder, 'public');
            }

            // 4. Create destination canvas
            $dst = imagecreatetruecolor($newWidth, $newHeight);

            // Handle transparency for PNG and WebP
            if ($mime == 'image/png' || $mime == 'image/webp') {
                imagealphablending($dst, false);
                imagesavealpha($dst, true);
                $transparent = imagecolorallocatealpha($dst, 255, 255, 255, 127);
                imagefilledrectangle($dst, 0, 0, $newWidth, $newHeight, $transparent);
            }

            // 5. Resize
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newWidth, $newHeight, $width, $height);

            // 6. Save and move to storage
            $filename = Str::random(20) . '.webp'; // Converting to WebP for best performance
            $tempPath = sys_get_temp_dir() . '/' . $filename;
            
            // Save as WebP (high compatibility and small size)
            imagewebp($dst, $tempPath, $quality);

            // Move to Laravel public storage
            $finalPath = $folder . '/' . $filename;
            Storage::disk('public')->put($finalPath, file_get_contents($tempPath));

            // 7. Cleanup
            @unlink($tempPath);
            imagedestroy($src);
            imagedestroy($dst);

            return $finalPath;

        } catch (\Exception $e) {
            // Ultimate fallback on error
            return $file->store($folder, 'public');
        }
    }
}
