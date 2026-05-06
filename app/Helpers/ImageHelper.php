<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageHelper
{
    /**
     * Upload an image directly to the public folder.
     */
    public static function upload($file, $directory = 'uploads/products'): string
    {
        if (!$file) return '';
        
        // Generate a clean filename: timestamp-random.extension
        $filename = time() . '-' . \Illuminate\Support\Str::random(10) . '.' . $file->getClientOriginalExtension();
        
        // Move the file directly to the public directory
        $file->move(public_path($directory), $filename);
        
        // Return the relative path from the public folder
        return $directory . '/' . $filename;
    }

    /**
     * Delete an image from the public folder.
     */
    public static function delete($path): bool
    {
        if (!$path) return false;

        // Clean path: remove 'storage/' if it exists, and trim slashes
        $cleanPath = str_replace('storage/', '', ltrim($path, '/'));
        $fullPath = public_path($cleanPath);
        
        if (file_exists($fullPath) && is_file($fullPath)) {
            return unlink($fullPath);
        }
        return false;
    }

    /**
     * Get the full URL of an image.
     */
    public static function getUrl($path, $fallbackDir = 'uploads/products'): string
    {
        if (!$path) return asset('images/placeholder.jpg');
        
        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        // Clean path for URL generation
        $cleanPath = str_replace('storage/', '', ltrim($path, '/'));
        
        return asset($cleanPath);
    }
}
