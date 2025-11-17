@extends('layouts.dashboard')

@section('title', 'Super Admin Dashboard')

@section('body-class', 'bg-gray-100 min-h-screen font-sans text-gray-900')

@section('content')
    @php($authUser = $user)

    <!-- Overlay (mobile) -->
    <div id="overlay" class="fixed inset-0 bg-black/50 hidden z-40 md:hidden"></div>

    <!-- Sidebar -->
    <aside id="sidebar" class="fixed top-0 left-0 h-full w-64 bg-white shadow-xl flex flex-col z-50 -translate-x-full md:translate-x-0 transition-transform duration-300">
        <div class="p-6 flex items-center justify-between border-b border-gray-200">
            <div class="flex items-center gap-3">
                <svg width="40" height="40" viewBox="0 0 192 192" xmlns="http://www.w3.org/2000/svg">
                    <rect width="192" height="192" fill="#6BBF59" rx="16" />
                    <rect x="48" y="36" width="60" height="24" fill="white" rx="4" />
                    <circle cx="136" cy="48" r="16" fill="#FF9933" />
                    <path d="M48 84 H108 V108 H72 V132 H108 V156 H48 Z" fill="white" />
                </svg>
                <div>
                    <p class="text-sm font-semibold text-gray-800">ERP CRM</p>
                    <p class="text-xs text-gray-500">Super Admin</p>
                </div>
            </div>
            <button id="closeSidebar" class="md:hidden text-gray-600 hover:text-gray-900 text-2xl">✕</button>
        </div>

        <nav class="flex-1 p-4 space-y-1 overflow-y-auto">
            <a href="{{ route('super-admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg bg-brand-green text-white font-medium shadow-sm hover:shadow-md transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" /></svg>
                <span class="ml-3">Super Admin Dashboard</span>
            </a>
            <span class="block px-4 text-xs uppercase tracking-wide text-gray-400 mt-6 mb-2">View Dashboards</span>
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-blue-50 hover:text-blue-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z" /></svg>
                <span class="ml-3">Admin Dashboard</span>
            </a>
            <a href="{{ route('manager.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-teal-50 hover:text-teal-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-.895 3-2s-1.343-2-3-2-3 .895-3 2 1.343 2 3 2zm0 2c-1.11 0-2.08.402-2.599 1-.28.35-.401.8-.401 1.25V18h6v-.75c0-.45-.12-.9-.401-1.25-.519-.598-1.489-1-2.599-1zM5 11h2a1 1 0 001-1V8a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zM5 13h2a1 1 0 011 1v2H4v-2a1 1 0 011-1zm12-2h2a1 1 0 001-1V8a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zm0 2h2a1 1 0 011 1v2h-4v-2a1 1 0 011-1z" /></svg>
                <span class="ml-3">Manager Dashboard</span>
            </a>
            <a href="{{ route('employee.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-orange-50 hover:text-orange-600 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                <span class="ml-3">Employee Dashboard</span>
            </a>
            <span class="block px-4 text-xs uppercase tracking-wide text-gray-400 mt-6 mb-2">Management</span>
            <a href="#" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-green-50 hover:text-brand-green transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                <span class="ml-3">Users</span>
            </a>
            <a href="#" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-green-50 hover:text-brand-green transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                <span class="ml-3">Leads</span>
            </a>
            <a href="#" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-green-50 hover:text-brand-green transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                <span class="ml-3">Reports</span>
            </a>
        </nav>

        <div class="p-4 border-t border-gray-200 mt-auto">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button class="w-full bg-brand-orange text-white py-3 rounded-lg font-semibold hover:bg-orange-600 transition shadow-sm hover:shadow-md">Logout</button>
            </form>
        </div>
    </aside>

    <!-- Main -->
    <div class="md:ml-64 min-h-screen flex flex-col">
        <!-- Topbar -->
        <header class="bg-white shadow-md py-4 px-6 flex justify-between items-center sticky top-0 z-30">
            <div class="flex items-center gap-4">
                <button type="button" id="openSidebar" class="md:hidden bg-brand-green hover:bg-green-600 text-white focus:outline-none p-2.5 rounded-lg transition shadow-lg hover:shadow-xl z-50 relative">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
                <h1 class="text-xl font-semibold text-gray-800">Super Admin Dashboard</h1>
            </div>
            <div class="flex items-center gap-4">
                <div class="text-right hidden sm:block">
                    <p class="text-sm font-medium text-gray-800">{{ $authUser->name }}</p>
                    <p class="text-xs text-gray-500 capitalize">{{ $authUser->role }}</p>
                </div>
                <img src="https://ui-avatars.com/api/?name={{ urlencode($authUser->name) }}&background=6BBF59&color=fff" class="w-10 h-10 rounded-full border-2 border-brand-green" alt="avatar" />
            </div>
        </header>

        <main class="flex-1 p-6 space-y-6">
            @if (session('status'))
                <div class="rounded-lg border border-green-200 bg-green-50 px-4 py-3 text-sm text-green-700">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="rounded-lg border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Welcome Card -->
            <div class="bg-gradient-to-r from-brand-green to-emerald-600 text-white p-6 rounded-xl shadow-md">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-semibold">Welcome, {{ $authUser->name }}</h2>
                        <p class="text-white/90 mt-1">You have full access to all system features as a Super Admin.</p>
                    </div>
                    <span class="self-start sm:self-auto inline-flex items-center px-4 py-1.5 rounded-full bg-white/20 text-sm font-semibold uppercase tracking-wide">Super Admin</span>
                </div>
            </div>

        </main>
    </div>
@endsection

@push('scripts')
    <script>
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const openBtn = document.getElementById('openSidebar');
        const closeBtn = document.getElementById('closeSidebar');
        const openSidebar = () => {
            sidebar.classList.remove('-translate-x-full');
            overlay.classList.remove('hidden');
        };
        const closeSidebar = () => {
            sidebar.classList.add('-translate-x-full');
            overlay.classList.add('hidden');
        };
        openBtn && openBtn.addEventListener('click', openSidebar);
        closeBtn && closeBtn.addEventListener('click', closeSidebar);
        overlay && overlay.addEventListener('click', closeSidebar);

    </script>
@endpush