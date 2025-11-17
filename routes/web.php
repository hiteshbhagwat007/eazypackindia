<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SuperAdmin\DashboardController;
use App\Http\Controllers\SuperAdmin\UserController as SuperAdminUserController;
use App\Http\Controllers\EmployeeDashboardController;
use App\Http\Controllers\CustomerController;

// Redirect root to login
Route::get('/', function () {
    return redirect()->route('login');
});

// Authentication Routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Dashboard Routes
Route::middleware('auth')->group(function () {
    Route::prefix('super-admin')->name('super-admin.')->group(function () {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::post('/users', [SuperAdminUserController::class, 'store'])->name('users.store');
        Route::patch('/users/{user}/status', [SuperAdminUserController::class, 'updateStatus'])->name('users.status');
        Route::delete('/users/{user}', [SuperAdminUserController::class, 'destroy'])->name('users.destroy');
    });

    Route::get('/admin/dashboard', function () {
        // Allow super admin or admin to access
        if (Auth::user()->isSuperAdmin() || Auth::user()->isAdmin()) {
            return view('dashboards.admin');
        }
        return redirect()->route('login')->withErrors(['error' => 'Unauthorized access']);
    })->name('admin.dashboard');

    Route::get('/manager/dashboard', function () {
        // Allow super admin or manager to access
        if (Auth::user()->isSuperAdmin() || Auth::user()->isManager()) {
            return view('dashboards.manager');
        }
        return redirect()->route('login')->withErrors(['error' => 'Unauthorized access']);
    })->name('manager.dashboard');

    Route::get('/employee/dashboard', [EmployeeDashboardController::class, 'index'])->name('employee.dashboard');
    Route::get('/purchase-orders/create', [EmployeeDashboardController::class, 'create'])->name('purchase-orders.create');
    Route::get('/purchase-orders', [EmployeeDashboardController::class, 'purchaseOrders'])->name('purchase-orders.index');
    Route::post('/purchase-orders', [EmployeeDashboardController::class, 'store'])->name('purchase-orders.store');
    Route::patch('/purchase-orders/{purchaseOrder}/status', [EmployeeDashboardController::class, 'updateStatus'])->name('purchase-orders.updateStatus');
    Route::post('/products', [EmployeeDashboardController::class, 'storeProduct'])->name('products.store');
    
    Route::get('/admin/all-orders', [EmployeeDashboardController::class, 'allOrders'])->name('admin.all-orders');
    
    // Customer Routes
    Route::get('/customers', [CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/create', [CustomerController::class, 'create'])->name('customers.create');
    Route::post('/customers', [CustomerController::class, 'store'])->name('customers.store');
    Route::get('/customers/{customer}/edit', [CustomerController::class, 'edit'])->name('customers.edit');
    Route::put('/customers/{customer}', [CustomerController::class, 'update'])->name('customers.update');
    Route::delete('/customers/{customer}', [CustomerController::class, 'destroy'])->name('customers.destroy');
    
    Route::get('/admin/user-management', function () {
        // Allow super admin or admin to access
        if (Auth::user()->isSuperAdmin() || Auth::user()->isAdmin()) {
            $managedUsers = \App\Models\User::whereIn('role', ['admin', 'manager', 'employee'])
                ->orderByDesc('created_at')
                ->get();
            $recentUsers = $managedUsers->take(5);
            return view('dashboards.user-management', compact('managedUsers', 'recentUsers'));
        }
        return redirect()->route('login')->withErrors(['error' => 'Unauthorized access']);
    })->name('admin.user-management');
});
