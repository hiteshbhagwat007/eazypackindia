@extends('layouts.dashboard')

@section('title', 'Manager Dashboard')

@section('body-class', 'bg-gray-100 min-h-screen font-sans text-gray-900')

@section('content')
    @php
        $authUser = Auth::user();

        $totalOrders = \App\Models\PurchaseOrder::count();
        $recentOrders = \App\Models\PurchaseOrder::where('created_at', '>=', now()->subDays(30))->count();
        $inTransitOrders = \App\Models\PurchaseOrder::where('status', 'in_transit')->count();
        $activeCustomers = \App\Models\PurchaseOrder::distinct('customer_name')->count('customer_name');

        $recentPurchaseOrders = \App\Models\PurchaseOrder::latest()->take(6)->get();
        $allOrders = \App\Models\PurchaseOrder::orderByDesc('created_at')->get();

        $topCustomers = \App\Models\PurchaseOrder::selectRaw('customer_name, COUNT(*) as orders_count, SUM(quantity * product_price) as total_value, MAX(created_at) as last_order_at')
            ->groupBy('customer_name')
            ->orderByDesc('total_value')
            ->take(4)
            ->get()
            ->map(function ($row) {
                return (object) [
                    'customer_name' => $row->customer_name,
                    'orders_count' => $row->orders_count,
                    'total_value' => $row->total_value,
                    'last_order_at' => $row->last_order_at ? \Illuminate\Support\Carbon::parse($row->last_order_at) : null,
                ];
            });

        $teamMembers = \App\Models\User::where('role', 'employee')
            ->orderBy('name')
            ->get()
            ->map(function ($member) {
                return (object) [
                    'user' => $member,
                    'total_orders' => 0,
                    'total_value' => 0,
                    'pending' => 0,
                    'in_transit' => 0,
                    'delivered' => 0,
                ];
            });
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
                    <p class="text-xs text-gray-500">Manager</p>
                </div>
            </div>
            <button id="closeSidebar" class="md:hidden text-gray-600 hover:text-gray-900 text-2xl">✕</button>
        </div>

        <nav class="flex-1 p-6 space-y-2 overflow-y-auto">
            <a href="{{ route('manager.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg bg-brand-green text-white font-medium shadow-sm hover:shadow-md transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="ml-3">Dashboard</span>
            </a>
            <span class="block px-4 text-xs uppercase tracking-wide text-gray-400 mt-6 mb-2">Shortcuts</span>
            <a href="{{ route('employee.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-green-50 hover:text-brand-green transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="ml-3">Employee Dashboard</span>
            </a>
            <a href="#" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-green-50 hover:text-brand-green transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="ml-3">Reports</span>
            </a>
            @if($authUser->isSuperAdmin())
                <a href="{{ route('super-admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-green-50 hover:text-brand-green transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <span class="ml-3">Back to Super Admin</span>
                </a>
            @endif
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
        <header class="bg-gradient-to-r from-purple-600 to-indigo-600 text-black shadow-md py-4 px-6 sticky top-0 z-30 flex justify-between items-center">
            <div class="flex items-center gap-4">
                <button type="button" id="openSidebar" class="md:hidden bg-brand-green hover:bg-green-600 text-white focus:outline-none p-2.5 rounded-lg transition shadow-lg hover:shadow-xl z-50 relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <div>
                    <h1 class="text-2xl font-bold">Manager Dashboard</h1>
                    <p class="text-purple-100 text-sm mt-1">Employee performance & order tracking</p>
                </div>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-medium">{{ $authUser->name }}</p>
                    <p class="text-xs text-purple-200 capitalize">{{ $authUser->role }}</p>
                </div>
                <img src="https://ui-avatars.com/api/?name={{ urlencode($authUser->name) }}&background=8b5cf6&color=fff" alt="profile" class="w-10 h-10 rounded-full border-2 border-white shadow-sm">
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

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-xl transition  border-green-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm text-gray-500 font-medium">Total Orders</h3>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($totalOrders) }}</p>
                            <p class="text-xs text-green-600 mt-1">All employees</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-lg">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-xl transition  border-blue-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm text-gray-500 font-medium">Recent Orders</h3>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($recentOrders) }}</p>
                            <p class="text-xs text-blue-600 mt-1">Last 30 days</p>
                        </div>
                        <div class="bg-blue-100 p-3 rounded-lg">
                            <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-xl transition  border-orange-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm text-gray-500 font-medium">In Transit</h3>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($inTransitOrders) }}</p>
                            <p class="text-xs text-orange-600 mt-1">Active shipments</p>
                        </div>
                        <div class="bg-orange-100 p-3 rounded-lg">
                            <svg class="w-8 h-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-xl transition border-purple-500">
                    <div class="flex items-center justify-between">
                        <div>
                            <h3 class="text-sm text-gray-500 font-medium">Active Customers</h3>
                            <p class="text-3xl font-bold text-gray-800 mt-2">{{ number_format($activeCustomers) }}</p>
                            <p class="text-xs text-purple-600 mt-1">Unique customers</p>
                        </div>
                        <div class="bg-purple-100 p-3 rounded-lg">
                            <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Employee Performance Overview</h2>
                        <p class="text-sm text-gray-500">Recent activity for team members (order attribution coming soon).</p>
                    </div>
                    <a href="{{ route('employee.dashboard') }}" class="px-4 py-2 bg-brand-green text-white rounded-lg hover:bg-green-600 transition text-sm font-medium">View Employee Workspace</a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse ($teamMembers as $member)
                        @php
                            $initials = collect(explode(' ', $member->user->name))->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->implode('');
                            $statusBadge = $member->user->status === 'active'
                                ? 'bg-green-100 text-green-700'
                                : 'bg-yellow-100 text-yellow-700';
                        @endphp
                        <div class="bg-gradient-to-br from-gray-50 to-white p-6 rounded-xl border border-gray-200 hover:shadow-lg transition">
                            <div class="flex items-center justify-between mb-4">
                                <div class="flex items-center space-x-3">
                                    <div class="w-12 h-12 rounded-full bg-gradient-to-br from-purple-500 to-indigo-500 flex items-center justify-center text-white font-bold text-lg">
                                        {{ $initials ?: strtoupper(mb_substr($member->user->name, 0, 1)) }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $member->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $member->user->email }}</p>
                                    </div>
                                </div>
                                <span class="px-3 py-1 text-xs rounded-full {{ $statusBadge }}">{{ ucfirst($member->user->status ?? 'inactive') }}</span>
                            </div>
                            <div class="space-y-3">
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Total Orders</span>
                                    <span class="font-bold text-gray-800">{{ $member->total_orders }}</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-sm text-gray-600">Total Value</span>
                                    <span class="font-bold text-green-600">₹{{ number_format($member->total_value, 2) }}</span>
                                </div>
                                <div class="pt-3 border-t border-gray-200">
                                    <div class="grid grid-cols-3 gap-2 text-center">
                                        <div>
                                            <p class="text-xs text-orange-600 font-medium">Pending</p>
                                            <p class="text-lg font-bold text-gray-800">{{ $member->pending }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-blue-600 font-medium">In Transit</p>
                                            <p class="text-lg font-bold text-gray-800">{{ $member->in_transit }}</p>
                                        </div>
                                        <div>
                                            <p class="text-xs text-green-600 font-medium">Delivered</p>
                                            <p class="text-lg font-bold text-gray-800">{{ $member->delivered }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-sm text-gray-500">No employees available.</p>
                    @endforelse
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Recent Purchase Orders</h2>
                    <div class="space-y-3">
                        @forelse ($recentPurchaseOrders as $order)
                            @php
                                $status = strtolower($order->status);
                                $statusMeta = [
                                    'delivered' => ['border' => 'border-green-500', 'bg' => 'bg-green-50', 'badge' => 'bg-green-100 text-green-700', 'label' => 'Delivered'],
                                    'in_transit' => ['border' => 'border-blue-500', 'bg' => 'bg-blue-50', 'badge' => 'bg-blue-100 text-blue-700', 'label' => 'In Transit'],
                                    'pending' => ['border' => 'border-orange-500', 'bg' => 'bg-orange-50', 'badge' => 'bg-orange-100 text-orange-700', 'label' => 'Pending'],
                                ][$status] ?? ['border' => 'border-gray-400', 'bg' => 'bg-gray-50', 'badge' => 'bg-gray-200 text-gray-700', 'label' => ucfirst($status)];
                            @endphp
                            <div class="border-l-4 {{ $statusMeta['border'] }} pl-4 py-3 {{ $statusMeta['bg'] }} rounded-r-lg">
                                <div class="flex justify-between items-start">
                                    <div class="flex-1 pr-4">
                                        <p class="font-semibold text-gray-800">{{ $order->po_number }}</p>
                                        <p class="text-sm text-gray-600">{{ $order->customer_name }} &mdash; {{ $order->product_name }}</p>
                                        <p class="text-xs text-gray-500 mt-1">Qty: {{ $order->quantity }} • ₹{{ number_format($order->total_amount, 2) }}</p>
                                    </div>
                                    <span class="px-3 py-1 text-xs rounded-full {{ $statusMeta['badge'] }} font-medium">{{ $statusMeta['label'] }}</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No purchase orders yet.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                    <h2 class="text-xl font-semibold text-gray-800 mb-4">Top Customers</h2>
                    <div class="space-y-3">
                        @forelse ($topCustomers as $customer)
                            @php
                                $nameParts = preg_split('/\s+/', trim($customer->customer_name));
                                $initials = strtoupper(collect($nameParts)->map(fn ($part) => mb_substr($part, 0, 1))->take(2)->implode(''));
                            @endphp
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition">
                                <div class="flex items-center space-x-4">
                                    <div class="w-12 h-12 rounded-full bg-brand-green flex items-center justify-center text-white font-bold text-lg">
                                        {{ $initials ?: 'C' }}
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-800">{{ $customer->customer_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $customer->orders_count }} orders • Last: {{ optional($customer->last_order_at)->diffForHumans() }}</p>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-semibold text-gray-800">₹{{ number_format($customer->total_value, 2) }}</p>
                                    <p class="text-xs text-gray-500">Total</p>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500">No customer insights available.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">All Orders Tracking</h2>
                        <p class="text-sm text-gray-500">Full visibility into purchase order progress.</p>
                    </div>
                    <div class="flex gap-2">
                        <span class="px-4 py-2 text-sm bg-gray-100 text-gray-700 rounded-lg font-medium">Pending: {{ $allOrders->where('status', 'pending')->count() }}</span>
                        <span class="px-4 py-2 text-sm bg-blue-100 text-blue-700 rounded-lg font-medium">In Transit: {{ $allOrders->where('status', 'in_transit')->count() }}</span>
                        <span class="px-4 py-2 text-sm bg-green-100 text-green-700 rounded-lg font-medium">Delivered: {{ $allOrders->where('status', 'delivered')->count() }}</span>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead class="bg-gray-50 text-gray-700 text-sm font-semibold">
                            <tr>
                                <th class="py-4 px-4 border-b">Order ID</th>
                                <th class="py-4 px-4 border-b">Customer</th>
                                <th class="py-4 px-4 border-b">Product</th>
                                <th class="py-4 px-4 border-b">Qty</th>
                                <th class="py-4 px-4 border-b">Amount</th>
                                <th class="py-4 px-4 border-b text-center">Status</th>
                                <th class="py-4 px-4 border-b text-sm text-gray-500">Created</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600">
                            @forelse ($allOrders as $order)
                                @php
                                    $status = strtolower($order->status);
                                    $chip = [
                                        'delivered' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Delivered'],
                                        'in_transit' => ['bg' => 'bg-blue-100', 'text' => 'text-blue-700', 'label' => 'In Transit'],
                                        'pending' => ['bg' => 'bg-orange-100', 'text' => 'text-orange-700', 'label' => 'Pending'],
                                    ][$status] ?? ['bg' => 'bg-gray-200', 'text' => 'text-gray-700', 'label' => ucfirst($status)];
                                @endphp
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="py-4 px-4 border-b font-medium text-gray-800">{{ $order->po_number }}</td>
                                    <td class="py-4 px-4 border-b">{{ $order->customer_name }}</td>
                                    <td class="py-4 px-4 border-b">{{ $order->product_name }}</td>
                                    <td class="py-4 px-4 border-b">{{ $order->quantity }}</td>
                                    <td class="py-4 px-4 border-b">₹{{ number_format($order->total_amount, 2) }}</td>
                                    <td class="py-4 px-4 border-b text-center">
                                        <span class="px-3 py-1 text-xs rounded-full {{ $chip['bg'] }} {{ $chip['text'] }} font-medium">{{ $chip['label'] }}</span>
                                    </td>
                                    <td class="py-4 px-4 border-b">{{ optional($order->created_at)->format('d M Y') }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="py-6 px-4 text-center text-sm text-gray-500">No orders recorded yet.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
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
        </script>
    @endpush
@endsection