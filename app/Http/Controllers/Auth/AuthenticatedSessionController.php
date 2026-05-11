<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Check for pending review
        if ($pending = $request->session()->pull('pending_review')) {
            try {
                (new \App\Http\Controllers\Public\ProductController)->executeReviewCreation(
                    $pending['product_id'],
                    auth()->id(),
                    $pending['rating'],
                    $pending['comment'],
                    $pending['images'] ?? []
                );

                $product = \App\Models\Product::find($pending['product_id']);
                if ($product) {
                    return redirect()->route('products.show', $product->slug)
                        ->with('success', 'Welcome back! Your review has been submitted.');
                }
            } catch (\Exception $e) {
                \Log::error('Failed to post pending review: ' . $e->getMessage());
            }
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
