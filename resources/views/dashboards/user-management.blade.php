@extends('layouts.dashboard')

@section('title', 'User Management - Admin')

@section('body-class', 'bg-gray-100 min-h-screen font-sans text-gray-900')

@section('content')
    @php
        $authUser = Auth::user();
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
            <a href="{{ route('admin.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-green-50 hover:text-brand-green transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                </svg>
                <span class="ml-3">Dashboard</span>
            </a>

            <a href="{{ route('admin.user-management') }}" class="flex items-center px-4 py-3 rounded-lg bg-brand-green text-white font-medium shadow-sm hover:shadow-md transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <span class="ml-3">User Management</span>
            </a>

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

            <a href="#" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-green-50 hover:text-brand-green transition">
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
            </a>
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
        <header class="bg-white from-indigo-600 to-purple-600 text-black shadow-md py-3 sm:py-4 px-4 sm:px-6 sticky top-0 z-30">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2 sm:gap-4">
                    <button type="button" id="openSidebar" class="md:hidden bg-brand-green hover:bg-green-600 text-white focus:outline-none p-2.5 rounded-lg transition shadow-lg hover:shadow-xl z-50 relative">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                        </svg>
                    </button>
                    <div>
                        <h1 class="text-lg sm:text-2xl font-bold">User Management</h1>
                        <p class="text-purple-100 text-xs sm:text-sm mt-1">Manage Admins, Managers & Employees</p>
                    </div>
                </div>
                <div class="flex items-center gap-2 sm:gap-4">
                    <div class="text-right hidden sm:block">
                        <p class="text-sm font-medium">{{ $authUser->name }}</p>
                        <p class="text-xs text-purple-200 capitalize">{{ $authUser->role }}</p>
                    </div>
                    <img src="https://ui-avatars.com/api/?name={{ urlencode($authUser->name) }}&background=6366f1&color=fff" alt="profile" class="w-8 h-8 sm:w-10 sm:h-10 rounded-full border-2 border-white shadow-sm">
                    <a href="{{ route('admin.dashboard') }}" class="hidden md:inline px-3 sm:px-4 py-1.5 sm:py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg text-xs sm:text-sm font-medium transition">Back to Dashboard</a>
                    @if($authUser->isSuperAdmin())
                        <a href="{{ route('super-admin.dashboard') }}" class="hidden md:inline px-3 sm:px-4 py-1.5 sm:py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg text-xs sm:text-sm font-medium transition">Super Admin</a>
                    @endif
                </div>
            </div>
        </header>

        <main class="flex-1 p-4 sm:p-6 space-y-6 md:space-y-6">
            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
                    {{ session('error') }}
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

            <div class="bg-gradient-to-r from-brand-green to-emerald-600 text-white p-4 sm:p-6 rounded-xl shadow-md mb-4 md:mb-0">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-semibold">User Management</h2>
                        <p class="text-white/90 mt-1 text-sm sm:text-base">Create and manage admin, manager, and employee accounts.</p>
                    </div>
                    <span class="self-start sm:self-auto inline-flex items-center px-3 sm:px-4 py-1.5 rounded-full bg-white/20 text-xs sm:text-sm font-semibold uppercase tracking-wide">Admin</span>
                </div>
            </div>

            <!-- Create User -->
            <div id="createUserSection" class="bg-white p-4 sm:p-6 rounded-xl shadow-md border border-gray-100 mb-4 md:mb-0">
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                    <div>
                        <h2 class="text-lg sm:text-xl font-semibold text-gray-800">Add Admin / Manager / Employee</h2>
                        <p class="text-xs sm:text-sm text-gray-500">Create new users directly from this dashboard.</p>
                    </div>
                    <button id="toggleCreateUser" class="w-full sm:w-auto px-5 py-2 bg-brand-green text-white rounded-lg hover:bg-green-600 transition shadow-sm hover:shadow-md font-medium">
                        <span id="toggleCreateUserLabel">Add New User</span>
                    </button>
                </div>

                <form id="createUserForm" class="mt-4 sm:mt-6 grid grid-cols-1 md:grid-cols-2 gap-4 hidden" method="POST" action="{{ route('super-admin.users.store') }}">
                    @csrf
                    <div>
                        <label for="create_name" class="block text-sm font-medium text-gray-700 mb-1">Full Name</label>
                        <input id="create_name" type="text" name="name" value="{{ old('name') }}" required class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent transition" placeholder="Full name">
                    </div>
                    <div>
                        <label for="create_email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                        <input id="create_email" type="email" name="email" value="{{ old('email') }}" required class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent transition" placeholder="user@example.com">
                    </div>
                    <div>
                        <label for="create_password" class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                        <input id="create_password" type="text" name="password" required class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent transition" placeholder="Plain text password">
                    </div>
                    <div>
                        <label for="create_role" class="block text-sm font-medium text-gray-700 mb-1">Role</label>
                        <select id="create_role" name="role" required class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent transition bg-white">
                            <option value="" disabled {{ old('role') ? '' : 'selected' }}>Select role</option>
                            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                            <option value="manager" {{ old('role') === 'manager' ? 'selected' : '' }}>Manager</option>
                            <option value="employee" {{ old('role') === 'employee' ? 'selected' : '' }}>Employee</option>
                        </select>
                    </div>
                    <div>
                        <label for="create_status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                        <select id="create_status" name="status" required class="w-full px-3 sm:px-4 py-2 sm:py-2.5 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent transition bg-white">
                            <option value="active" selected>Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                    <div class="md:col-span-2 flex flex-col sm:flex-row items-stretch sm:items-center justify-end gap-3">
                        <button type="button" id="cancelCreateUser" class="w-full sm:w-auto px-4 py-2 text-sm font-medium text-gray-600 hover:text-gray-800">Cancel</button>
                        <button type="submit" class="w-full sm:w-auto px-5 py-2 bg-brand-orange text-white rounded-lg hover:bg-orange-600 transition shadow-sm hover:shadow-md font-semibold">Create User</button>
                    </div>
                </form>
            </div>

            <!-- Users Section -->
            <div class="bg-white p-4 sm:p-6 rounded-xl shadow-md border border-gray-100 mb-4 md:mb-0">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-4 sm:mb-6 gap-4">
                    <div>
                        <h2 class="text-lg sm:text-xl font-semibold text-gray-800">Admins, Managers & Employees</h2>
                        <p class="text-xs sm:text-sm text-gray-500">Manage non super admin accounts from here.</p>
                    </div>
                    <div class="flex flex-wrap gap-2 text-xs">
                        <span class="px-3 py-1 rounded-full bg-green-100 text-green-700">Active</span>
                        <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700">Inactive</span>
                    </div>
                </div>
                @if ($managedUsers->isEmpty())
                    <p class="text-sm text-gray-500">No admin, manager, or employee accounts found. Use the "Add New User" button above to create one.</p>
                @else
                    <!-- Mobile Card View -->
                    <div class="block md:hidden space-y-5 mt-4">
                        @foreach ($managedUsers as $managedUser)
                            <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition">
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex-1 min-w-0">
                                        <h3 class="font-semibold text-gray-900 text-base truncate">{{ $managedUser->name }}</h3>
                                        <p class="text-sm text-gray-600 truncate mt-1">{{ $managedUser->email }}</p>
                                    </div>
                                    <span class="inline-flex items-center px-3 py-1 text-xs rounded-full ml-2 {{ $managedUser->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                        {{ ucfirst($managedUser->status) }}
                                    </span>
                                </div>
                                
                                <div class="space-y-2 border-t border-gray-200 pt-3">
                                    <div class="flex justify-between items-center">
                                        <span class="text-sm text-gray-600">Role:</span>
                                        <span class="text-sm font-medium text-gray-900 capitalize">{{ $managedUser->role }}</span>
                                    </div>
                                    <div class="flex flex-col sm:flex-row gap-2 pt-2">
                                        <form method="POST" action="{{ route('super-admin.users.status', $managedUser) }}" class="flex-1">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $managedUser->status === 'active' ? 'inactive' : 'active' }}">
                                            <button type="submit" class="w-full px-3 py-2 text-xs font-semibold rounded-lg border border-gray-200 hover:border-indigo-400 hover:text-indigo-600 transition bg-white">
                                                {{ $managedUser->status === 'active' ? 'Set Inactive' : 'Set Active' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('super-admin.users.destroy', $managedUser) }}" class="delete-user-form flex-1">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="w-full px-3 py-2 text-xs font-semibold rounded-lg border border-red-200 text-red-600 hover:bg-red-50 transition">
                                                Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Desktop Table View -->
                    <div class="block overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead class="bg-gray-50 text-gray-700 text-sm font-semibold">
                                <tr>
                                    <th class="py-3 px-4 border-b border-gray-200">Name</th>
                                    <th class="py-3 px-4 border-b border-gray-200">Email</th>
                                    <th class="py-3 px-4 border-b border-gray-200">Role</th>
                                    <th class="py-3 px-4 border-b border-gray-200">Status</th>
                                    <th class="py-3 px-4 border-b border-gray-200 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="text-gray-700">
                                @foreach ($managedUsers as $managedUser)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="py-3 px-4 border-b border-gray-100 font-medium text-gray-900">{{ $managedUser->name }}</td>
                                        <td class="py-3 px-4 border-b border-gray-100">{{ $managedUser->email }}</td>
                                        <td class="py-3 px-4 border-b border-gray-100 capitalize">{{ $managedUser->role }}</td>
                                        <td class="py-3 px-4 border-b border-gray-100">
                                            <span class="inline-flex items-center px-3 py-1 text-xs rounded-full {{ $managedUser->status === 'active' ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                                {{ ucfirst($managedUser->status) }}
                                            </span>
                                        </td>
                                        <td class="py-3 px-4 border-b border-gray-100 text-right">
                                            <div class="flex justify-end items-center gap-2">
                                                <form method="POST" action="{{ route('super-admin.users.status', $managedUser) }}">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="{{ $managedUser->status === 'active' ? 'inactive' : 'active' }}">
                                                    <button type="submit" class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-gray-200 hover:border-indigo-400 hover:text-indigo-600 transition">
                                                        {{ $managedUser->status === 'active' ? 'Set Inactive' : 'Set Active' }}
                                                    </button>
                                                </form>
                                                <form method="POST" action="{{ route('super-admin.users.destroy', $managedUser) }}" class="delete-user-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="px-3 py-1.5 text-xs font-semibold rounded-lg border border-red-200 text-red-600 hover:bg-red-50 transition">
                                                        Delete
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>

            <!-- Recent Users -->
            <div class="hidden md:block bg-white p-4 sm:p-6 rounded-xl shadow-md border border-gray-100 mb-4 md:mb-0">
                <h2 class="text-lg sm:text-xl font-semibold text-gray-800 mb-4">Recently Added</h2>
                @if ($recentUsers->isEmpty())
                    <p class="text-sm text-gray-500">No recent users found.</p>
                @else
                    @php($roleBadgeClasses = [
                        'admin' => 'bg-indigo-100 text-indigo-700',
                        'manager' => 'bg-teal-100 text-teal-700',
                        'employee' => 'bg-emerald-100 text-emerald-700',
                    ])
                    <ul class="divide-y divide-gray-100">
                        @foreach ($recentUsers as $recentUser)
                            <li class="py-3 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-2 sm:gap-0">
                                <div class="flex-1 min-w-0">
                                    <p class="font-medium text-gray-900 truncate">{{ $recentUser->name }}</p>
                                    <p class="text-sm text-gray-500 truncate">{{ $recentUser->email }}</p>
                                </div>
                                @php($recentRoleBadge = $roleBadgeClasses[$recentUser->role] ?? 'bg-gray-100 text-gray-700')
                                <span class="text-xs font-semibold uppercase px-3 py-1 rounded-full {{ $recentRoleBadge }} self-start sm:self-auto">
                                    {{ ucfirst($recentUser->role) }}
                                </span>
                            </li>
                        @endforeach
                    </ul>
                @endif
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

            const toggleCreateUserBtn = document.getElementById('toggleCreateUser');
            const toggleCreateUserLabel = document.getElementById('toggleCreateUserLabel');
            const createUserForm = document.getElementById('createUserForm');
            const cancelCreateUserBtn = document.getElementById('cancelCreateUser');

            const showForm = () => {
                createUserForm.classList.remove('hidden');
                toggleCreateUserLabel.textContent = 'Hide Form';
            };

            const hideForm = () => {
                createUserForm.classList.add('hidden');
                toggleCreateUserLabel.textContent = 'Add New User';
            };

            toggleCreateUserBtn && toggleCreateUserBtn.addEventListener('click', () => {
                createUserForm.classList.contains('hidden') ? showForm() : hideForm();
            });
            cancelCreateUserBtn && cancelCreateUserBtn.addEventListener('click', hideForm);

            document.querySelectorAll('.delete-user-form').forEach((form) => {
                form.addEventListener('submit', (event) => {
                    const confirmed = confirm('Are you sure you want to delete this user?');
                    if (!confirmed) {
                        event.preventDefault();
                    }
                });
            });
        </script>
    @endpush
@endsection

