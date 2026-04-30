<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageHelper
{
    /**
     * Upload an image to a specific directory.
     */
    public static function upload($file, $directory = 'general'): string
    {
        // Use the global helper function to reduce size and convert to WebP
        return reduceImageSize($file, $directory);
    }

    /**
     * Delete an image from storage.
     */
    public static function delete($path): bool
    {
        if ($path && Storage::disk('public')->exists($path)) {
            return Storage::disk('public')->delete($path);
        }
        return false;
    }

    /**
     * Get the full URL of an image.
     */
    public static function getUrl($path, $fallbackDir = 'images'): string
    {
        if (!$path) return asset('images/placeholder.jpg');
        
        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        if (!Str::contains($path, '/')) {
            return asset($fallbackDir . '/' . $path);
        }

        return Storage::disk('public')->url($path);
    }
}
