<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Helpers\ImageHelper;
use Illuminate\Http\Request;

class UploadController extends BaseController
{
    /**
     * Handle CKEditor image upload
     */
    public function editorUpload(Request $request)
    {
        if ($request->hasFile('upload')) {
            $path = ImageHelper::upload($request->file('upload'), 'editor');
            $url = ImageHelper::getUrl($path);

            return response()->json([
                'uploaded' => true,
                'url' => $url
            ]);
        }

        return response()->json(['uploaded' => false, 'error' => ['message' => 'No file uploaded']], 400);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:5120',
            'folder' => 'nullable|string'
        ]);

        if ($request->hasFile('image')) {
            $folder = $request->input('folder', 'uploads/about');
            $path = ImageHelper::upload($request->file('image'), $folder);
            
            return response()->json([
                'success' => true,
                'path' => $path,
                'url' => asset($path)
            ]);
        }

        return response()->json(['success' => false, 'message' => 'No file uploaded'], 400);
    }

    /**
     * List all uploaded images
     */
    public function list(Request $request)
    {
        $folder = $request->input('folder', 'uploads/about');
        $directory = public_path($folder);
        
        if (!file_exists($directory)) {
            return response()->json(['success' => true, 'files' => []]);
        }

        $files = array_diff(scandir($directory), ['.', '..']);
        $result = [];
        
        foreach ($files as $file) {
            if (is_file($directory . '/' . $file)) {
                $result[] = [
                    'name' => $file,
                    'url' => asset($folder . '/' . $file),
                    'path' => $folder . '/' . $file,
                    'size' => round(filesize($directory . '/' . $file) / 1024, 2) . ' KB',
                    'time' => filemtime($directory . '/' . $file)
                ];
            }
        }

        // Sort by time descending
        usort($result, function($a, $b) {
            return $b['time'] - $a['time'];
        });

        return response()->json([
            'success' => true,
            'files' => $result
        ]);
    }
}

