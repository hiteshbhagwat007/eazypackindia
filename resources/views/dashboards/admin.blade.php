@extends('layouts.dashboard')

@section('title', 'Admin Dashboard')

@section('body-class', 'bg-gray-100 min-h-screen font-sans text-gray-900')

@section('content')
    @php
        $authUser = Auth::user();
        $currentRoute = request()->route()->getName();

        $stats = [
            'total_users' => \App\Models\User::count(),
            'total_admins' => \App\Models\User::where('role', 'admin')->count(),
            'total_managers' => \App\Models\User::where('role', 'manager')->count(),
            'total_employees' => \App\Models\User::where('role', 'employee')->count(),
            'active_users' => \App\Models\User::whereIn('role', ['admin', 'manager', 'employee'])->where('status', 'active')->count(),
        ];

        $totalOrders = \App\Models\PurchaseOrder::count();
        
        // This month orders count
        $thisMonthOrders = \App\Models\PurchaseOrder::whereBetween('created_at', [
            now()->startOfMonth(),
            now()->endOfMonth()
        ])->count();
        
        // Last month orders count for percentage calculation
        $lastMonthOrders = \App\Models\PurchaseOrder::whereBetween('created_at', [
            now()->subMonth()->startOfMonth(),
            now()->subMonth()->endOfMonth()
        ])->count();
        
        // Calculate percentage change (comparing this month with last month)
        $orderPercentageChange = 0;
        if ($lastMonthOrders > 0) {
            $orderPercentageChange = round((($thisMonthOrders - $lastMonthOrders) / $lastMonthOrders) * 100);
        } elseif ($thisMonthOrders > 0) {
            $orderPercentageChange = 100; // If no orders last month but orders this month
        }
        
        // Recent orders (last 30 days)
        $recentOrders = \App\Models\PurchaseOrder::where('created_at', '>=', now()->subDays(30))->count();
        
        // Order tracking (in transit)
        $inTransitOrders = \App\Models\PurchaseOrder::where('status', 'in_transit')->count();
        
        // Active customers (customers with orders in last 30 days)
        $activeCustomers = \App\Models\PurchaseOrder::where('created_at', '>=', now()->subDays(30))
            ->distinct('customer_name')
            ->count('customer_name');
        
        // Monthly revenue
        $monthlyRevenue = \App\Models\PurchaseOrder::selectRaw('SUM(quantity * product_price) as total')
            ->whereBetween('created_at', [now()->startOfMonth(), now()->endOfMonth()])
            ->value('total') ?? 0;
        
        // Recent purchase orders for dashboard (only 3)
        $recentPurchaseOrders = \App\Models\PurchaseOrder::orderByDesc('created_at')->take(3)->get();

        $recentActivities = collect([
            \App\Models\PurchaseOrder::latest()->first(),
            \App\Models\User::latest()->first(),
        ])->filter()->map(function ($item) {
            if ($item instanceof \App\Models\PurchaseOrder) {
                return [
                    'icon_bg' => 'bg-green-100',
                    'icon_color' => 'text-green-600',
                    'message' => "Purchase order {$item->po_number} created for {$item->customer_name}",
                    'meta' => "{$item->quantity} units • ₹" . number_format($item->total_amount, 2),
                    'time' => optional($item->created_at)->diffForHumans(),
                ];
            }

            if ($item instanceof \App\Models\User) {
                return [
                    'icon_bg' => 'bg-blue-100',
                    'icon_color' => 'text-blue-600',
                    'message' => "New user registered: {$item->name}",
                    'meta' => ucfirst($item->role) . " • " . optional($item->created_at)->diffForHumans(),
                    'time' => optional($item->created_at)->diffForHumans(),
                ];
            }

            return null;
        })->filter()->take(4);

        $totalRevenue = \App\Models\PurchaseOrder::selectRaw('SUM(quantity * product_price) as total')->value('total') ?? 0;
    @endphp

    <div id="overlay" class="fixed inset-0 bg-black/50 hidden z-40 md:hidden"></div>

    <aside id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-white shadow-xl flex flex-col z-50 -translate-x-full md:translate-x-0 transition-transform duration-300">
        <div class="p-6 flex items-center justify-between border-b border-gray-200">
            <div class="flex items-center gap-3">
                <svg width="44" height="44" viewBox="0 0 192 192" xmlns="http://www.w3.org/2000/svg">
                    <rect width="192" height="192" fill="#6BBF59" rx="16" />
                    <rect x="48" y="36" width="60" height="24" fill="white" rx="4" />
                    <circle cx="136" cy="48" r="16" fill="#FF9933" />
                    <path d="M48 84 H108 V108 H72 V132 H108 V156 H48 Z" fill="white" />
                </svg>
                <div>
                    <p class="text-sm font-semibold text-gray-800">ERP CRM</p>
                    <p class="text-xs text-gray-500">Admin</p>
                </div>
            </div>
            <button id="closeSidebar" class="md:hidden text-gray-600 hover:text-gray-900 text-2xl">✕</button>
        </div>

        <nav class="flex-1 p-6 space-y-2 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg bg-brand-green text-white font-medium shadow-sm hover:shadow-md transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="ml-3">Dashboard</span>
            </a>

            <a href="{{ route('admin.user-management') }}" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-green-50 hover:text-brand-green transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span class="ml-3">User Management</span>
            </a>

            <!-- Customers Dropdown -->
            <div class="relative">
                <button id="customersDropdownBtn" class="w-full flex items-center px-4 py-3 rounded-lg {{ in_array($currentRoute, ['customers.index', 'customers.create', 'customers.edit']) ? 'bg-brand-green text-white font-medium shadow-sm' : 'text-gray-700 hover:bg-green-50 hover:text-brand-green' }} transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                    <span class="ml-3 flex-1 text-left">Customers</span>
                    <svg id="customersDropdownArrow" class="w-4 h-4 transition-transform {{ in_array($currentRoute, ['customers.index', 'customers.create', 'customers.edit']) ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>
                <div id="customersDropdown" class="ml-4 mt-2 space-y-1 {{ in_array($currentRoute, ['customers.index', 'customers.create', 'customers.edit']) ? '' : 'hidden' }}">
                    <a href="{{ route('customers.create') }}" class="flex items-center px-4 py-2 rounded-lg {{ $currentRoute == 'customers.create' ? 'bg-brand-green text-white' : 'text-gray-600 hover:bg-green-50 hover:text-brand-green' }} transition text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        <span>Create Customer</span>
                    </a>
                    <a href="{{ route('customers.index') }}" class="flex items-center px-4 py-2 rounded-lg {{ $currentRoute == 'customers.index' ? 'bg-brand-green text-white' : 'text-gray-600 hover:bg-green-50 hover:text-brand-green' }} transition text-sm">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        <span>View Customer</span>
                    </a>
                </div>
            </div>

            <a href="{{ route('manager.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-green-50 hover:text-brand-green transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="ml-3">Manager Overview</span>
            </a>

            <a href="{{ route('employee.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-green-50 hover:text-brand-green transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="ml-3">Employee Workspace</span>
            </a>

            <a href="{{ route('admin.all-orders') }}" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-green-50 hover:text-brand-green transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="ml-3">All Orders</span>
            </a>

            <!-- <a href="#" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-green-50 hover:text-brand-green transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="ml-3">Reports</span>
            </a>

            <a href="#" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-green-50 hover:text-brand-green transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="ml-3">Settings</span>
            </a> -->
        </nav>

        <div class="p-6 border-t border-gray-200 mt-auto">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="w-full bg-brand-green text-white py-3 rounded-lg font-semibold hover:bg-green-600 transition shadow-sm hover:shadow-md">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    <div class="md:ml-64 min-h-screen flex flex-col">
        <header class="bg-white from-indigo-600 to-purple-600 text-black shadow-md py-4 px-6 sticky top-0 z-30">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-4">
                    <button type="button" id="openSidebar" class="md:hidden bg-brand-green hover:bg-green-600 text-white focus:outline-none p-2.5 rounded-lg transition shadow-lg hover:shadow-xl z-50 relative">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-2xl font-bold">Admin Dashboard</h1>
                        <p class="text-purple-100 text-sm mt-1">System overview & management</p>
                    </div>
                </div>
                <div class="flex items-center gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-medium">{{ $authUser->name }}</p>
                        <p class="text-xs text-purple-200 capitalize">{{ $authUser->role }}</p>
                    </div>
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($authUser->name) }}&background=6366f1&color=fff" alt="profile" class="w-10 h-10 rounded-full border-2 border-white shadow-sm">
                    @if($authUser->isSuperAdmin())
                        <a href="{{ route('super-admin.dashboard') }}" class="hidden md:inline px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg text-sm font-medium transition">Back to Super Admin</a>
                    @endif
                </div>
            </div>
        </header>

        <main class="flex-1 p-6 space-y-6">
            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="bg-gradient-to-r from-brand-green to-emerald-600 text-white p-6 rounded-xl shadow-md">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-semibold">Welcome, {{ $authUser->name }}</h2>
                        <p class="text-white/90 mt-1">You have administrative access to manage users and operations.</p>
                    </div>
                    <span class="self-start sm:self-auto inline-flex items-center px-4 py-1.5 rounded-full bg-white/20 text-sm font-semibold uppercase tracking-wide">Admin</span>
                </div>
            </div>

<!-- Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    @php(
        $summaryCards = [
            [
                'label' => 'Total Users',
                'value' => number_format($stats['total_users']),
                'badge' => 'All records',
                'iconColor' => 'text-brand-green',
                'iconBg' => 'bg-green-100',
            ],
            [
                'label' => 'Admins',
                'value' => number_format($stats['total_admins']),
                'badge' => 'Manage roles',
                'iconColor' => 'text-blue-600',
                'iconBg' => 'bg-blue-100',
            ],
            [
                'label' => 'Managers',
                'value' => number_format($stats['total_managers']),
                'badge' => 'Team leads',
                'iconColor' => 'text-teal-600',
                'iconBg' => 'bg-teal-100',
            ],
            [
                'label' => 'Employees',
                'value' => number_format($stats['total_employees']),
                'badge' => 'Active workforce',
                'iconColor' => 'text-brand-orange',
                'iconBg' => 'bg-orange-100',
            ],
            [
                'label' => 'Active Users',
                'value' => number_format($stats['active_users']),
                'badge' => 'Currently enabled',
                'iconColor' => 'text-purple-600',
                'iconBg' => 'bg-purple-100',
            ],
        ]
    )
    @foreach ($summaryCards as $card)
        <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <h3 class="text-sm text-gray-500 font-medium">{{ $card['label'] }}</h3>
                    <p class="text-3xl font-bold text-gray-800 mt-2">{{ $card['value'] }}</p>
                    <p class="text-xs mt-1 text-gray-500">{{ $card['badge'] }}</p>
                </div>
                <div class="p-3 rounded-lg {{ $card['iconBg'] }}">
                    <svg class="w-8 h-8 {{ $card['iconColor'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1" />
                    </svg>
                </div>
            </div>
        </div>
    @endforeach
</div>

<!-- Order Stats -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
    <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm text-gray-500 font-medium">Total Purchase Orders</h3>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($totalOrders) }}</p>
                <div class="flex items-center gap-1 mt-1">
                    @if($orderPercentageChange > 0)
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                        </svg>
                        <span class="text-xs text-green-600 font-medium">{{ abs($orderPercentageChange) }}% from last month</span>
                    @elseif($orderPercentageChange < 0)
                        <svg class="w-4 h-4 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                        </svg>
                        <span class="text-xs text-red-600 font-medium">{{ abs($orderPercentageChange) }}% from last month</span>
                    @else
                        <span class="text-xs text-gray-500">No change from last month</span>
                    @endif
                </div>
            </div>
            <div class="p-3 rounded-lg bg-blue-100">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm text-gray-500 font-medium">Recent Orders</h3>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($recentOrders) }}</p>
                <p class="text-xs mt-1 text-gray-500">Last 30 days</p>
            </div>
            <div class="p-3 rounded-lg bg-green-100">
                <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm text-gray-500 font-medium">Order Tracking</h3>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($inTransitOrders) }}</p>
                <p class="text-xs mt-1 text-gray-500">In transit</p>
            </div>
            <div class="p-3 rounded-lg bg-orange-100">
                <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm text-gray-500 font-medium">Active Customers</h3>
                <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($activeCustomers) }}</p>
                <p class="text-xs mt-1 text-gray-500">Last 30 days</p>
            </div>
            <div class="p-3 rounded-lg bg-purple-100">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-lg transition transform hover:-translate-y-0.5 border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <h3 class="text-sm text-gray-500 font-medium">Monthly Revenue</h3>
                <p class="text-3xl font-bold text-gray-800 mt-2">₹{{ number_format($monthlyRevenue, 2) }}</p>
                <p class="text-xs mt-1 text-gray-500">This month</p>
            </div>
            <div class="p-3 rounded-lg bg-emerald-100">
                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
    </div>
</div>

            <!-- Recent Order Tracking Table
            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Recent Orders</h2>
                        <p class="text-sm text-gray-500">Latest 3 purchase orders. <a href="{{ route('admin.all-orders') }}" class="text-brand-green hover:text-green-600 font-medium">View All Orders →</a></p>
                    </div>
                    <div class="flex flex-wrap gap-2 text-xs">
                        <span class="px-3 py-1 rounded-full bg-orange-100 text-orange-700">Pending</span>
                        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700">In Transit</span>
                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700">Delivered</span>
                    </div>
                </div>
                @if ($recentPurchaseOrders->isEmpty())
                    <p class="text-sm text-gray-500">No purchase orders found.</p>
                @else
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-50 text-gray-700 text-sm font-semibold">
                                <tr>
                                    <th class="py-3 px-4 border-b border-gray-200">PO Number</th>
                                    <th class="py-3 px-4 border-b border-gray-200">Customer</th>
                                    <th class="py-3 px-4 border-b border-gray-200">Product</th>
                                    <th class="py-3 px-4 border-b border-gray-200">SKU</th>
                                    <th class="py-3 px-4 border-b border-gray-200">Quantity</th>
                                    <th class="py-3 px-4 border-b border-gray-200">Unit Price</th>
                                    <th class="py-3 px-4 border-b border-gray-200">Total Amount</th>
                                    <th class="py-3 px-4 border-b border-gray-200">Order Date</th>
                                    <th class="py-3 px-4 border-b border-gray-200">Delivery Date</th>
                                    <th class="py-3 px-4 border-b border-gray-200">Status</th>
                                    <th class="py-3 px-4 border-b border-gray-200 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @foreach ($recentPurchaseOrders as $order)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="py-3 px-4 border-b border-gray-100 font-medium text-gray-900">{{ $order->po_number ?? 'N/A' }}</td>
                                        <td class="py-3 px-4 border-b border-gray-100">{{ $order->customer_name ?? 'N/A' }}</td>
                                        <td class="py-3 px-4 border-b border-gray-100">{{ $order->product_name ?? 'N/A' }}</td>
                                        <td class="py-3 px-4 border-b border-gray-100 text-sm text-gray-600">{{ $order->sku_number ?? 'N/A' }}</td>
                                        <td class="py-3 px-4 border-b border-gray-100">{{ number_format($order->quantity ?? 0) }}</td>
                                        <td class="py-3 px-4 border-b border-gray-100">₹{{ number_format($order->product_price ?? 0, 2) }}</td>
                                        <td class="py-3 px-4 border-b border-gray-100 font-semibold text-gray-900">₹{{ number_format($order->total_amount ?? 0, 2) }}</td>
                                        <td class="py-3 px-4 border-b border-gray-100 text-sm text-gray-600">
                                            @if($order->created_at)
                                                <div>{{ $order->created_at->format('M d, Y') }}</div>
                                                <div class="text-xs text-gray-500">{{ $order->created_at->format('h:i A') }}</div>
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 border-b border-gray-100">{{ $order->delivery_date ? $order->delivery_date->format('M d, Y') : 'N/A' }}</td>
                                        <td class="py-3 px-4 border-b border-gray-100">
                                            @if(strtolower($order->status ?? 'pending') === 'delivered')
                                                <span class="inline-flex items-center px-3 py-1 text-xs rounded-full bg-green-100 text-green-700">Delivered</span>
                                            @elseif(strtolower($order->status ?? 'pending') === 'in_transit')
                                                <span class="inline-flex items-center px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-700">In Transit</span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 text-xs rounded-full bg-orange-100 text-orange-700">Pending</span>
                                            @endif
                                        </td>
                                        <td class="py-3 px-4 border-b border-gray-100 text-right">
                                            <div class="flex justify-end items-center gap-2">
                                                @if($order->status !== 'in_transit')
                                                    <form method="POST" action="{{ route('purchase-orders.updateStatus', $order) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="in_transit">
                                                        <button type="submit" class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-blue-200 text-blue-600 hover:bg-blue-50 transition">
                                                            Mark In Transit
                                                        </button>
                                                    </form>
                                                @endif
                                                @if($order->status !== 'delivered')
                                                    <form method="POST" action="{{ route('purchase-orders.updateStatus', $order) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="delivered">
                                                        <button type="submit" class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-green-200 text-green-600 hover:bg-green-50 transition">
                                                            Mark Delivered
                                                        </button>
                                                    </form>
                                                @endif
                                                @if($order->status !== 'pending')
                                                    <form method="POST" action="{{ route('purchase-orders.updateStatus', $order) }}">
                                                        @csrf
                                                        @method('PATCH')
                                                        <input type="hidden" name="status" value="pending">
                                                        <button type="submit" class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-orange-200 text-orange-600 hover:bg-orange-50 transition">
                                                            Mark Pending
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div> -->

            <div class="bg-gradient-to-r from-brand-green to-emerald-600 text-white p-6 rounded-xl shadow-md border border-green-200">
                <h2 class="text-xl font-semibold mb-4">Quick Actions</h2>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="{{ route('admin.user-management') }}" class="flex items-center justify-center space-x-2 px-4 py-3 bg-white/20 text-white rounded-lg hover:bg-white/30 transition shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                        </svg>
                        <span class="font-medium">Add User</span>
                    </a>
                    <a href="{{ route('employee.dashboard') }}" class="flex items-center justify-center space-x-2 px-4 py-3 bg-white/20 text-white rounded-lg hover:bg-white/30 transition shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="font-medium">View Orders</span>
                    </a>
                    <a href="{{ route('employee.dashboard') }}" class="flex items-center justify-center space-x-2 px-4 py-3 bg-white/20 text-white rounded-lg hover:bg-white/30 transition shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="font-medium">Add Item</span>
                    </a>
                    <button id="quickSettings" class="flex items-center justify-center space-x-2 px-4 py-3 bg-white/20 text-white rounded-lg hover:bg-white/30 transition shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <span class="font-medium">Settings</span>
                    </button>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">System Activity</h2>
                <div class="space-y-4">
                    @forelse ($recentActivities as $activity)
                        <div class="flex items-start space-x-3">
                            <div class="w-8 h-8 rounded-full {{ $activity['icon_bg'] }} flex items-center justify-center flex-shrink-0">
                                <svg class="w-4 h-4 {{ $activity['icon_color'] }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-sm text-gray-800 font-medium">{{ $activity['message'] }}</p>
                                <p class="text-xs text-gray-500 mt-1">{{ $activity['meta'] }}</p>
                            </div>
                            <span class="text-xs text-gray-400">{{ $activity['time'] }}</span>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No recent activity recorded.</p>
                    @endforelse
                </div>
            </div>

          

            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 gap-4">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Financial Overview</h2>
                        <p class="text-sm text-gray-500">Combined revenue from all recorded purchase orders.</p>
                    </div>
                    <span class="text-lg font-semibold text-green-600">₹{{ number_format($totalRevenue, 2) }}</span>
                </div>
                <p class="text-sm text-gray-600">Track total purchase value across the organisation. Use the manager dashboard for detailed order-level tracking.</p>
            </div>
        </main>
    </div>

    @push('scripts')
        <script>
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('overlay');
            const openSidebar = document.getElementById('openSidebar');
            const closeSidebar = document.getElementById('closeSidebar');

            if (openSidebar && closeSidebar && sidebar && overlay) {
                openSidebar.addEventListener('click', () => {
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.remove('hidden');
                });

                closeSidebar.addEventListener('click', () => {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                });

                overlay.addEventListener('click', () => {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                });
            }

            const quickSettingsBtn = document.getElementById('quickSettings');
            if (quickSettingsBtn) {
                quickSettingsBtn.addEventListener('click', () => {
                    // Scroll to Account Details section (settings-like)
                    const accountDetails = document.getElementById('accountDetailsSection');
                    if (accountDetails) {
                        accountDetails.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    } else {
                        alert('Settings feature coming soon!');
                    }
                });
            }

            // Customers Dropdown Toggle
            const customersDropdownBtn = document.getElementById('customersDropdownBtn');
            const customersDropdown = document.getElementById('customersDropdown');
            const customersDropdownArrow = document.getElementById('customersDropdownArrow');

            if (customersDropdownBtn && customersDropdown) {
                customersDropdownBtn.addEventListener('click', function(e) {
                    e.preventDefault();
                    customersDropdown.classList.toggle('hidden');
                    customersDropdownArrow.classList.toggle('rotate-180');
                });
            }
        </script>
    @endpush
@endsection

