<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Store a newly created admin/manager/employee user.
     */
    public function store(Request $request): RedirectResponse
    {
        $this->ensureSuperAdmin();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:4'],
            'role' => ['required', Rule::in(['admin', 'manager', 'employee'])],
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => $validated['password'], // Plain text storage per project requirements
            'role' => $validated['role'],
            'status' => $validated['status'],
        ]);

        // Redirect back to the page where the user was created
        $referer = $request->headers->get('referer');
        if ($referer && str_contains($referer, 'user-management')) {
            return redirect()
                ->route('admin.user-management')
                ->with('status', 'User created successfully.');
        }
        
        return redirect()
            ->route('admin.user-management')
            ->with('status', 'User created successfully.');
    }

    /**
     * Update the status (active/inactive) for a user.
     */
    public function updateStatus(Request $request, User $user): RedirectResponse
    {
        $this->ensureSuperAdmin();

        if ($user->role === 'super_admin') {
            $referer = $request->headers->get('referer');
            if ($referer && str_contains($referer, 'user-management')) {
                return redirect()
                    ->route('admin.user-management')
                    ->with('error', 'You cannot change status of another super admin.');
            }
            return redirect()
                ->route('admin.user-management')
                ->with('error', 'You cannot change status of another super admin.');
        }

        $validated = $request->validate([
            'status' => ['required', Rule::in(['active', 'inactive'])],
        ]);

        $user->update(['status' => $validated['status']]);

        // Redirect back to the page where the update was made
        $referer = $request->headers->get('referer');
        if ($referer && str_contains($referer, 'user-management')) {
            return redirect()
                ->route('admin.user-management')
                ->with('status', 'User status updated successfully.');
        }

        return redirect()
            ->route('admin.user-management')
            ->with('status', 'User status updated successfully.');
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user): RedirectResponse
    {
        $this->ensureSuperAdmin();

        if ($user->role === 'super_admin') {
            $referer = request()->headers->get('referer');
            if ($referer && str_contains($referer, 'user-management')) {
                return redirect()
                    ->route('admin.user-management')
                    ->with('error', 'You cannot delete another super admin.');
            }
            return redirect()
                ->route('admin.user-management')
                ->with('error', 'You cannot delete another super admin.');
        }

        $user->delete();

        // Redirect back to the page where the delete was made
        $referer = request()->headers->get('referer');
        if ($referer && str_contains($referer, 'user-management')) {
            return redirect()
                ->route('admin.user-management')
                ->with('status', 'User deleted successfully.');
        }

        return redirect()
            ->route('admin.user-management')
            ->with('status', 'User deleted successfully.');
    }

    /**
     * Ensure only super admins can access the action.
     */
    private function ensureSuperAdmin(): void
    {
        abort_if(!auth()->check() || !auth()->user()->isSuperAdmin(), 403);
    }
}
