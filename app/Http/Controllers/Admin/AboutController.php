<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageContent;
use App\Models\PageContentVersion;
use Illuminate\Http\Request;

class AboutController extends Controller
{
    public function edit()
    {
        $about = PageContent::where('slug', 'about')->firstOrFail();
        $versions = PageContentVersion::where('page_content_id', $about->id)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        return view('admin.about.edit', compact('about', 'versions'));
    }

    public function update(Request $request)
    {
        $about = PageContent::where('slug', 'about')->firstOrFail();
        
        $content = $request->input('content');

        // Post-process bio to split by newline into array
        if (isset($content['founders']['list'])) {
            foreach ($content['founders']['list'] as $index => $founder) {
                if (isset($founder['bio']) && is_string($founder['bio'])) {
                    $content['founders']['list'][$index]['bio'] = array_filter(explode("\n", str_replace("\r", "", $founder['bio'])));
                }
            }
        }

        $about->update([
            'content' => $content,
            'status' => $request->input('status', $about->status)
        ]);

        // Save Version History
        PageContentVersion::create([
            'page_content_id' => $about->id,
            'content' => $content,
            'status' => $about->status,
            'user_id' => auth()->id(),
            'version_note' => $request->input('version_note', 'Auto-save')
        ]);

        if ($request->ajax()) {
            return response()->json([
                'success' => true,
                'message' => 'About page saved successfully.'
            ]);
        }

        return redirect()->back()->with('success', 'About page updated successfully.');
    }

    /**
     * Restore a previous version
     */
    public function restore($id)
    {
        $version = PageContentVersion::findOrFail($id);
        $about = PageContent::findOrFail($version->page_content_id);
        
        $about->update([
            'content' => $version->content,
            'status' => $version->status
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Version restored successfully.',
            'content' => $version->content
        ]);
    }
}
