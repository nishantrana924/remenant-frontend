<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     * All authenticated users are admins and redirected to admin dashboard.
     */
    public function index(Request $request): RedirectResponse
    {
        return redirect()->route('admin.dashboard');
    }

    /**
     * Display the admin dashboard.
     * Only accessible to admin users (protected by admin middleware).
     */
    public function admin(Request $request): View
    {
        return view('admin.dashboard');
    }
}
