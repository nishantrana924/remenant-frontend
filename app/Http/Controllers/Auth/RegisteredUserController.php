<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class RegisteredUserController extends Controller
{
    /**
     * Display the registration view.
     */
    public function create(Request $request): View
    {
        $referer = $request->headers->get('referer');
        if ($referer && $referer !== url()->current() && $referer !== route('login') && $referer !== route('register')) {
            session(['url.intended' => $referer]);
        }
        return view('auth.register');
    }

    /**
     * Handle an incoming registration request.
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'phone' => ['required', 'string', 'regex:/^[0-9]{10}$/'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => 2, // Default to regular user (ID 2)
        ]);

        event(new Registered($user));

        Auth::login($user);

        // Check for pending review
        if ($pending = $request->session()->pull('pending_review')) {
            try {
                (new \App\Http\Controllers\Public\ProductController)->executeReviewCreation(
                    $pending['product_id'],
                    $user->id,
                    $pending['rating'],
                    $pending['comment'],
                    $pending['images'] ?? []
                );

                $product = \App\Models\Product::find($pending['product_id']);
                if ($product) {
                    return redirect()->route('products.show', $product->slug)
                        ->with('success', 'Account created! Your review has also been submitted.');
                }
            } catch (\Exception $e) {
                \Log::error('Failed to post pending review after registration: ' . $e->getMessage());
            }
        }

        if ($user->isAdmin()) {
            return redirect()->route('admin.dashboard');
        }

        return redirect()->intended(route('home'));
    }
}
