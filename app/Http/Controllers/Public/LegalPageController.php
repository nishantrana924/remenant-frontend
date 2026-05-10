<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use App\Models\PageContent;
use Illuminate\Http\Request;

class LegalPageController extends Controller
{
    public function show($slug)
    {
        $page = PageContent::where('slug', $slug)->firstOrFail();
        return view('public.legal', ['data' => $page->content, 'slug' => $slug]);
    }
}
