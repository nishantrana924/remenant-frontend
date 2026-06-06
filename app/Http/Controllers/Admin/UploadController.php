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
        return $this->processUpload($request, 'upload', 'editor');
    }

    public function upload(Request $request)
    {
        return $this->processUpload($request, 'image', $request->input('folder', 'uploads/about'));
    }

    private function processUpload(Request $request, string $inputKey, string $folder)
    {
        if (!$request->hasFile($inputKey)) {
            return response()->json(['uploaded' => false, 'error' => ['message' => 'No file uploaded']], 400);
        }

        $file = $request->file($inputKey);

        // 1. Validate Size & MIME using Laravel rules first
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            $inputKey => 'required|file|image|mimes:jpeg,png,jpg,webp,gif|max:5120'
        ]);

        if ($validator->fails()) {
            \Illuminate\Support\Facades\Log::channel('upload_security')->warning('Upload blocked: Validation failed.', [
                'errors' => $validator->errors()->toArray(),
                'ip' => $request->ip()
            ]);
            return response()->json(['uploaded' => false, 'error' => ['message' => 'Invalid file format or size.']], 400);
        }

        $originalName = $file->getClientOriginalName();
        $extension = strtolower($file->getClientOriginalExtension());
        $mime = strtolower($file->getMimeType());

        // 2. Strict Blocklist Validation
        $blockedExtensions = ['php', 'phtml', 'php3', 'php4', 'php5', 'php7', 'phps', 'phar', 'svg', 'exe', 'js', 'html', 'htm', 'htaccess', 'sh', 'bat', 'cmd'];
        if (in_array($extension, $blockedExtensions) || strpos($mime, 'svg') !== false) {
            \Illuminate\Support\Facades\Log::channel('upload_security')->alert('Upload blocked: Forbidden extension/mime.', [
                'file' => $originalName,
                'mime' => $mime,
                'ip' => $request->ip()
            ]);
            return response()->json(['uploaded' => false, 'error' => ['message' => 'Forbidden file type.']], 400);
        }

        // 3. Double Extension Attack Prevention
        $parts = explode('.', $originalName);
        if (count($parts) > 2) {
            foreach ($parts as $part) {
                if (in_array(strtolower($part), $blockedExtensions)) {
                    \Illuminate\Support\Facades\Log::channel('upload_security')->alert('Upload blocked: Double extension attack detected.', [
                        'file' => $originalName,
                        'ip' => $request->ip()
                    ]);
                    return response()->json(['uploaded' => false, 'error' => ['message' => 'Invalid filename structure.']], 400);
                }
            }
        }

        // 4. Validate actual image content (getimagesize)
        $imageInfo = @getimagesize($file->getRealPath());
        if (!$imageInfo) {
            \Illuminate\Support\Facades\Log::channel('upload_security')->alert('Upload blocked: Fake image/malformed file.', [
                'file' => $originalName,
                'ip' => $request->ip()
            ]);
            return response()->json(['uploaded' => false, 'error' => ['message' => 'Malformed image file.']], 400);
        }

        try {
            $path = ImageHelper::upload($file, $folder);
            $url = ImageHelper::getUrl($path);

            return response()->json([
                'uploaded' => true,
                'success' => true,
                'path' => $path,
                'url' => $url
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::channel('upload_security')->error('Upload processing failed: ' . $e->getMessage());
            return response()->json(['uploaded' => false, 'error' => ['message' => 'Server error processing file.']], 500);
        }
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

