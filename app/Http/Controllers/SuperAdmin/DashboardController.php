<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Display the super admin dashboard.
     */
    public function __invoke(): View
    {
        abort_if(!auth()->check() || !auth()->user()->isSuperAdmin(), 403);

        $user = auth()->user();
        $managedUsers = User::whereIn('role', ['admin', 'manager', 'employee'])
            ->orderByDesc('created_at')
            ->get();

        $stats = [
            'total_users' => User::count(),
            'total_admins' => $managedUsers->where('role', 'admin')->count(),
            'total_managers' => $managedUsers->where('role', 'manager')->count(),
            'total_employees' => $managedUsers->where('role', 'employee')->count(),
            'active_users' => $managedUsers->where('status', 'active')->count(),
        ];

        $recentUsers = $managedUsers->take(5);

        return view('dashboards.super-admin', compact('user', 'managedUsers', 'stats', 'recentUsers'));
    }
}
