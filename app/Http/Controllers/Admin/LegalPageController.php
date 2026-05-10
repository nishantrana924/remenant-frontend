<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PageContent;
use Illuminate\Http\Request;

class LegalPageController extends Controller
{
    public function index()
    {
        $pages = PageContent::whereIn('slug', [
            'privacy-policy',
            'terms-and-conditions',
            'shipping-guide',
            'refund-policy'
        ])->get();

        return view('admin.legal.index', compact('pages'));
    }

    public function edit($id)
    {
        $page = PageContent::findOrFail($id);
        return view('admin.legal.edit', compact('page'));
    }

    public function update(Request $request, $id)
    {
        $page = PageContent::findOrFail($id);
        
        $request->validate([
            'title' => 'required|string',
            'seo_title' => 'nullable|string',
            'seo_description' => 'nullable|string',
            'content' => 'required|array'
        ]);

        $content = $page->content;
        $content['title'] = $request->title;
        $content['seo'] = [
            'title' => $request->seo_title,
            'description' => $request->seo_description
        ];
        $content['sections'] = $request->content['sections'] ?? [];
        $content['last_updated'] = now()->format('M d, Y');

        $page->update([
            'content' => $content
        ]);

        return redirect()->route('admin.legal.index')->with('success', 'Page updated successfully.');
    }
}
