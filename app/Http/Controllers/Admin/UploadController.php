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
}
