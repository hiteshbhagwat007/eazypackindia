@extends('layouts.dashboard')

@section('title', 'All Orders - Admin')

@section('body-class', 'bg-gray-100 min-h-screen font-sans text-gray-900')

@section('content')
    @php
        $authUser = Auth::user();
    @endphp

    {{-- Overlay (only used on small screens) --}}
    <div id="overlay" class="fixed inset-0 bg-black/50 hidden z-40 md:hidden" aria-hidden="true"></div>

    {{-- Sidebar --}}
    <aside id="sidebar"
           class="fixed top-0 left-0 h-full w-3/4 max-w-xs sm:w-64 md:w-64 lg:w-72 bg-white shadow-xl flex flex-col z-50 -translate-x-full md:translate-x-0 transition-transform duration-300"
           aria-label="Sidebar">
        <div class="p-6 flex items-center justify-between border-b border-gray-200">
            <div class="flex items-center gap-3">
                <!-- Logo -->
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

            <button id="closeSidebar" class="md:hidden text-gray-600 hover:text-gray-900 text-2xl" aria-label="Close sidebar">✕</button>
        </div>

        <nav class="flex-1 p-6 space-y-2 overflow-y-auto">
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-green-50 hover:text-brand-green transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="ml-3 truncate">Dashboard</span>
            </a>

            <a href="#" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-green-50 hover:text-brand-green transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span class="ml-3 truncate">User Management</span>
            </a>

            <a href="{{ route('manager.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-green-50 hover:text-brand-green transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
                <span class="ml-3 truncate">Manager Overview</span>
            </a>

            <a href="{{ route('employee.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-green-50 hover:text-brand-green transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span class="ml-3 truncate">Employee Workspace</span>
            </a>

            <a href="{{ route('admin.all-orders') }}" class="flex items-center px-4 py-3 rounded-lg bg-brand-green text-white font-medium shadow-sm hover:shadow-md transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="ml-3 truncate">All Orders</span>
            </a>

            <a href="#" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-green-50 hover:text-brand-green transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <span class="ml-3 truncate">Reports</span>
            </a>

            <a href="#" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-green-50 hover:text-brand-green transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                </svg>
                <span class="ml-3 truncate">Settings</span>
            </a>
        </nav>

        <div class="p-6 border-t border-gray-200 mt-auto">
            <form action="{{ route('logout') }}" method="POST" class="space-y-2">
                @csrf
                <button type="submit" class="w-full bg-brand-green text-white py-3 rounded-lg font-semibold hover:bg-green-600 transition shadow-sm hover:shadow-md">
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- Main content area. md:ml-64 / lg:ml-72 to account for sidebar width --}}
    <div class="min-h-screen flex flex-col md:ml-64 lg:ml-72 transition-all duration-300">
        <header class="bg-white text-black shadow-md py-3 sm:py-4 px-4 sm:px-6 sticky top-0 z-30">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2 sm:gap-4">
                    <button type="button" id="openSidebar" class="md:hidden bg-brand-green hover:bg-green-600 text-white focus:outline-none p-2.5 rounded-lg transition shadow-lg hover:shadow-xl z-50 relative" aria-controls="sidebar" aria-expanded="false" aria-label="Open sidebar">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>

                    <div>
                        <h1 class="text-lg sm:text-2xl font-bold">All Orders</h1>
                        <p class="text-gray-500 text-xs sm:text-sm mt-1">Complete order tracking &amp; management</p>
                    </div>
                </div>

                <div class="flex items-center gap-2 sm:gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-medium">{{ $authUser->name }}</p>
                        <p class="text-xs text-gray-500 capitalize">{{ $authUser->role }}</p>
                    </div>

                    <img src="https://ui-avatars.com/api/?name={{ urlencode($authUser->name) }}&background=6366f1&color=fff" alt="profile" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full border-2 border-white shadow-sm">
                    <a href="{{ route('admin.dashboard') }}" class="hidden md:inline px-3 sm:px-4 py-1.5 sm:py-2 bg-white/20 hover:bg-white/30 text-black rounded-lg text-xs sm:text-sm font-medium transition">Back to Dashboard</a>

                    @if($authUser->isSuperAdmin())
                        <a href="{{ route('super-admin.dashboard') }}" class="hidden md:inline px-3 sm:px-4 py-1.5 sm:py-2 bg-white/20 hover:bg-white/30 text-black rounded-lg text-xs sm:text-sm font-medium transition">Super Admin</a>
                    @endif
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 space-y-4 sm:space-y-6">
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

            <!-- Order Tracking Card -->
            <div class="bg-white p-4 sm:p-6 rounded-xl shadow-md border border-gray-100">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-6 gap-4">
                    <div>
                        <h2 class="text-lg sm:text-xl font-semibold text-gray-800">Order Tracking</h2>
                        <p class="text-xs sm:text-sm text-gray-500">Track and update status of all purchase orders. Total: {{ $allPurchaseOrders->count() }} orders</p>
                    </div>
                    <div class="flex flex-wrap gap-2 text-xs">
                        <span class="px-3 py-1 rounded-full bg-orange-100 text-orange-700">Pending</span>
                        <span class="px-3 py-1 rounded-full bg-blue-100 text-blue-700">In Transit</span>
                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700">Delivered</span>
                    </div>
                </div>

                @if ($allPurchaseOrders->isEmpty())
                    <p class="text-sm text-gray-500">No purchase orders found.</p>
                @else
                    {{-- Mobile Card View (visible on small screens) --}}
                    <div class="block md:hidden space-y-4">
                        @foreach ($allPurchaseOrders as $order)
                            @php
                                $status = strtolower($order->status ?? 'pending');
                                $chipBg = $status === 'delivered' ? 'bg-green-100' : ($status === 'in_transit' ? 'bg-blue-100' : 'bg-orange-100');
                                $chipText = $status === 'delivered' ? 'text-green-700' : ($status === 'in_transit' ? 'text-blue-700' : 'text-orange-700');
                                $label = $status === 'delivered' ? 'Delivered' : ($status === 'in_transit' ? 'In Transit' : 'Pending');
                            @endphp
                            <article class="bg-gray-50 border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition">
                                <div class="flex justify-between items-start mb-3">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-semibold text-gray-900 text-base truncate">{{ $order->po_number ?? 'N/A' }}</h3>
                                        <p class="text-sm text-gray-600 truncate mt-1">{{ $order->customer_name ?? 'N/A' }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 text-xs rounded-full ml-2 {{ $chipBg }} {{ $chipText }} font-medium">{{ $label }}</span>
                                </div>

                                <div class="space-y-2 border-t border-gray-200 pt-3 text-sm">
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Product</span>
                                        <span class="font-medium text-gray-900 truncate ml-2">{{ $order->product_name ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">SKU</span>
                                        <span class="text-gray-900">{{ $order->sku_number ?? 'N/A' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Qty</span>
                                        <span class="font-medium text-gray-900">{{ number_format($order->quantity ?? 0) }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-600">Unit</span>
                                        <span class="text-gray-900">₹{{ number_format($order->product_price ?? 0, 2) }}</span>
                                    </div>

                                    <div class="flex justify-between pt-2 border-t border-gray-100">
                                        <span class="text-sm font-semibold text-gray-700">Total</span>
                                        <span class="text-base font-bold text-brand-green">₹{{ number_format($order->total_amount ?? 0, 2) }}</span>
                                    </div>

                                    <div class="grid grid-cols-2 gap-3 pt-2 border-t border-gray-100 text-xs text-gray-600">
                                        <div>
                                            <p class="mb-1">Order Date</p>
                                            <p class="text-gray-900">
                                                @if($order->created_at)
                                                    {{ $order->created_at->format('M d, Y') }}
                                                @else
                                                    N/A
                                                @endif
                                            </p>
                                        </div>
                                        <div>
                                            <p class="mb-1">Delivery Date</p>
                                            <p class="text-gray-900">{{ $order->delivery_date ? $order->delivery_date->format('M d, Y') : 'N/A' }}</p>
                                        </div>
                                    </div>

                                    <div class="flex flex-col gap-2 pt-3 border-t border-gray-100">
                                        @if($order->status !== 'in_transit')
                                            <form method="POST" action="{{ route('purchase-orders.updateStatus', $order) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="in_transit">
                                                <button type="submit" class="w-full px-3 py-2 text-xs font-semibold rounded-lg border border-blue-200 text-blue-600 hover:bg-blue-50 transition bg-white">Mark In Transit</button>
                                            </form>
                                        @endif

                                        @if($order->status !== 'delivered')
                                            <form method="POST" action="{{ route('purchase-orders.updateStatus', $order) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="delivered">
                                                <button type="submit" class="w-full px-3 py-2 text-xs font-semibold rounded-lg border border-green-200 text-green-600 hover:bg-green-50 transition bg-white">Mark Delivered</button>
                                            </form>
                                        @endif

                                        @if($order->status !== 'pending')
                                            <form method="POST" action="{{ route('purchase-orders.updateStatus', $order) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="pending">
                                                <button type="submit" class="w-full px-3 py-2 text-xs font-semibold rounded-lg border border-orange-200 text-orange-600 hover:bg-orange-50 transition bg-white">Mark Pending</button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </article>
                        @endforeach
                    </div>

                    {{-- Desktop Table View (visible on md+) - replacement --}}
<div class="overflow-x-auto mt-2">
    <table class="w-full min-w-full table-auto text-left border-collapse">
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
            @foreach ($allPurchaseOrders as $order)
                <tr class="hover:bg-gray-50 transition">
                    <td class="py-3 px-4 border-b border-gray-100 font-medium text-gray-900 whitespace-nowrap">{{ $order->po_number ?? 'N/A' }}</td>
                    <td class="py-3 px-4 border-b border-gray-100 whitespace-nowrap">{{ $order->customer_name ?? 'N/A' }}</td>
                    <td class="py-3 px-4 border-b border-gray-100">{{ $order->product_name ?? 'N/A' }}</td>
                    <td class="py-3 px-4 border-b border-gray-100 text-sm text-gray-600 whitespace-nowrap">{{ $order->sku_number ?? 'N/A' }}</td>
                    <td class="py-3 px-4 border-b border-gray-100 whitespace-nowrap">{{ number_format($order->quantity ?? 0) }}</td>
                    <td class="py-3 px-4 border-b border-gray-100 whitespace-nowrap">₹{{ number_format($order->product_price ?? 0, 2) }}</td>
                    <td class="py-3 px-4 border-b border-gray-100 font-semibold text-gray-900 whitespace-nowrap">₹{{ number_format($order->total_amount ?? 0, 2) }}</td>
                    <td class="py-3 px-4 border-b border-gray-100 text-sm text-gray-600 whitespace-nowrap">
                        @if($order->created_at)
                            <div>{{ $order->created_at->format('M d, Y') }}</div>
                            <div class="text-xs text-gray-500">{{ $order->created_at->format('h:i A') }}</div>
                        @else
                            N/A
                        @endif
                    </td>
                    <td class="py-3 px-4 border-b border-gray-100 whitespace-nowrap">{{ $order->delivery_date ? $order->delivery_date->format('M d, Y') : 'N/A' }}</td>
                    <td class="py-3 px-4 border-b border-gray-100 whitespace-nowrap">
                        @if(strtolower($order->status ?? 'pending') === 'delivered')
                            <span class="inline-flex items-center px-3 py-1 text-xs rounded-full bg-green-100 text-green-700">Delivered</span>
                        @elseif(strtolower($order->status ?? 'pending') === 'in_transit')
                            <span class="inline-flex items-center px-3 py-1 text-xs rounded-full bg-blue-100 text-blue-700">In Transit</span>
                        @else
                            <span class="inline-flex items-center px-3 py-1 text-xs rounded-full bg-orange-100 text-orange-700">Pending</span>
                        @endif
                    </td>
                    <td class="py-3 px-4 border-b border-gray-100 text-right">
                        <div class="flex justify-end items-center gap-2 flex-wrap">
                            {{-- action buttons (same as before) --}}
                            @if($order->status !== 'in_transit')
                                <form method="POST" action="{{ route('purchase-orders.updateStatus', $order) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="in_transit">
                                    <button type="submit" class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-blue-200 text-blue-600 hover:bg-blue-50 transition">Mark In Transit</button>
                                </form>
                            @endif

                            @if($order->status !== 'delivered')
                                <form method="POST" action="{{ route('purchase-orders.updateStatus', $order) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="delivered">
                                    <button type="submit" class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-green-200 text-green-600 hover:bg-green-50 transition">Mark Delivered</button>
                                </form>
                            @endif

                            @if($order->status !== 'pending')
                                <form method="POST" action="{{ route('purchase-orders.updateStatus', $order) }}">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="status" value="pending">
                                    <button type="submit" class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-orange-200 text-orange-600 hover:bg-orange-50 transition">Mark Pending</button>
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
            </div>
        </main>
    </div>

    @push('scripts')
        <script>
            (function () {
                const sidebar = document.getElementById('sidebar');
                const overlay = document.getElementById('overlay');
                const openBtn = document.getElementById('openSidebar');
                const closeBtn = document.getElementById('closeSidebar');

                function isSidebarOpen() {
                    return !sidebar.classList.contains('-translate-x-full');
                }

                function openSidebar() {
                    sidebar.classList.remove('-translate-x-full');
                    overlay.classList.remove('hidden');
                    openBtn.setAttribute('aria-expanded', 'true');
                    // prevent body scroll on small screens when sidebar open
                    document.documentElement.classList.add('overflow-hidden');
                }

                function closeSidebar() {
                    sidebar.classList.add('-translate-x-full');
                    overlay.classList.add('hidden');
                    openBtn.setAttribute('aria-expanded', 'false');
                    document.documentElement.classList.remove('overflow-hidden');
                }

                if (openBtn) {
                    openBtn.addEventListener('click', () => {
                        openSidebar();
                    });
                }

                if (closeBtn) {
                    closeBtn.addEventListener('click', () => {
                        closeSidebar();
                    });
                }

                if (overlay) {
                    overlay.addEventListener('click', () => {
                        closeSidebar();
                    });
                }

                // Close on ESC
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && isSidebarOpen()) {
                        closeSidebar();
                    }
                });

                // Ensure correct layout if window resizes from small -> md
                window.addEventListener('resize', () => {
                    // If viewport is >= 768px (md), ensure overlay hidden and body scroll restored
                    if (window.innerWidth >= 768) {
                        overlay.classList.add('hidden');
                        document.documentElement.classList.remove('overflow-hidden');
                        openBtn && openBtn.setAttribute('aria-expanded', 'false');
                        // keep sidebar visible on md+ (default state)
                        sidebar.classList.remove('-translate-x-full');
                    } else {
                        // On small screens, by default hide sidebar (unless user opened it)
                        if (!isSidebarOpen()) {
                            sidebar.classList.add('-translate-x-full');
                        }
                    }
                });

                // Initial resize check to set correct state if user lands on small/large screen
                window.dispatchEvent(new Event('resize'));
            })();
        </script>
    @endpush
@endsection
