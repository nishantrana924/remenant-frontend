<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageHelper
{
    /**
     * Upload an image directly to the public folder.
     */
    public static function upload($file, $directory = 'products'): string
    {
        if (!$file) return '';
        
        // Ensure directory starts with 'uploads/' but doesn't repeat it
        $directory = trim($directory, '/');
        if (!\Illuminate\Support\Str::startsWith($directory, 'uploads')) {
            $directory = 'uploads/' . $directory;
        }
        
        $uploadPath = public_path($directory);
        
        if (!file_exists($uploadPath)) {
            mkdir($uploadPath, 0777, true);
        }
        
        // Generate a clean filename
        $filename = time() . '-' . \Illuminate\Support\Str::random(10) . '.' . $file->getClientOriginalExtension();
        
        // Move file directly to public directory
        $file->move($uploadPath, $filename);
        
        // Return relative path from public root
        return $directory . '/' . $filename;
    }

    /**
     * Delete an image from the public folder.
     */
    public static function delete($path): bool
    {
        if (!$path) return false;

        $cleanPath = ltrim($path, '/');
        
        // Remove storage/ prefix if it exists (legacy)
        if (\Illuminate\Support\Str::startsWith($cleanPath, 'storage/')) {
            $storagePath = storage_path('app/public/' . substr($cleanPath, 8));
            if (file_exists($storagePath)) {
                @unlink($storagePath);
            }
            $cleanPath = substr($cleanPath, 8);
        }

        $fullPath = public_path($cleanPath);
        if (file_exists($fullPath) && is_file($fullPath)) {
            return @unlink($fullPath);
        }

        return false;
    }

    /**
     * Get the full URL of an image (Prioritizing Public folder).
     */
    public static function getUrl($path, $fallbackDir = 'products'): string
    {
        if (!$path) return asset('images/placeholder.jpg');
        
        if (\Illuminate\Support\Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        // Clean path and remove storage/ prefix if it exists
        $cleanPath = ltrim($path, '/');
        if (\Illuminate\Support\Str::startsWith($cleanPath, 'storage/')) {
            $cleanPath = substr($cleanPath, 8);
            
            // Check in actual storage just in case (for old files)
            if (\Illuminate\Support\Facades\Storage::disk('public')->exists($cleanPath)) {
                return asset('storage/' . $cleanPath);
            }
        }

        // 1. Check if it exists in Public folder (The new standard)
        if (file_exists(public_path($cleanPath))) {
            return asset($cleanPath);
        }

        // 2. Try in public/images/ as fallback
        $imgFallback = 'images/' . $cleanPath;
        if (file_exists(public_path($imgFallback))) {
            return asset($imgFallback);
        }

        // 3. Try with provided fallback directory (e.g. products)
        $dirFallback = trim($fallbackDir, '/') . '/' . $cleanPath;
        if (file_exists(public_path($dirFallback))) {
            return asset($dirFallback);
        }
        
        // 4. Try images/products/ as common fallback
        $prodFallback = 'images/products/' . $cleanPath;
        if (file_exists(public_path($prodFallback))) {
            return asset($prodFallback);
        }

        // Return placeholder if nothing works
        return asset('images/placeholder.jpg');
    }
}
