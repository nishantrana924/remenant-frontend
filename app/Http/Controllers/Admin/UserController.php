<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index()
    {
        // withTrashed() ensures soft-deleted users still appear in the admin list
        // Filter for role_id 2 (Regular Customers)
        $items = User::where('role_id', 2)->withTrashed()->with('orders')->latest()->paginate(50);
        return view('admin.customers.index', compact('items'));
    }

    public function admins()
    {
        // Filter for role_id 1 (Administrators)
        $items = User::where('role_id', 1)->withTrashed()->with('orders')->latest()->paginate(50);
        return view('admin.admins.index', compact('items'));
    }

    public function storeAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email',
            'phone' => 'nullable|string|max:20',
            'password' => 'required|string|min:8',
        ]);

        $admin = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'password' => Hash::make($request->password),
            'role_id' => 1, // 1 = Admin
        ]);

        $admin->email_verified_at = now();
        $admin->save();

        return redirect()->route('admin.admins.index')->with('success', 'Administrator added successfully.');
    }

    public function updateRole(Request $request, $id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->role_id = (int)$request->role;
        $user->save();

        return redirect()->back()->with('success', 'User role updated successfully.');
    }

    public function show($id)
    {
        $item = User::withTrashed()->with(['orders.orderItems.product', 'addresses'])->findOrFail($id);
        
        $stats = [
            'total_spent' => $item->orders()->where('payment_status', 'paid')->sum('total_amount'),
            'total_orders' => $item->orders()->count(),
            'avg_order_value' => $item->orders()->count() > 0 ? $item->orders()->avg('total_amount') : 0,
        ];

        return view('admin.customers.show', compact('item', 'stats'));
    }

    /**
     * Deactivate (soft-delete) a user account.
     */
    public function destroy($id)
    {
        $user = User::findOrFail($id);
        $user->delete();

        return redirect()->back()->with('success', "Account for {$user->name} has been deactivated.");
    }

    /**
     * Restore a soft-deleted (deactivated) user account.
     */
    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return redirect()->back()->with('success', "Account for {$user->name} has been restored.");
    }

    /**
     * Permanently delete a user account.
     */
    public function forceDelete($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $name = $user->name;
        $user->forceDelete();

        return redirect()->back()->with('success', "Account for {$name} has been permanently deleted.");
    }
}
