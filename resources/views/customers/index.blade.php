<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Customers - ERP CRM</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <script>
    tailwind.config = {
      theme: {
        extend: {
          colors: {
            brand: {
              green: '#6BBF59',
              orange: '#FF9933'
            }
          }
        }
      }
    }
  </script>
  <style>
    .sidebar {
      transition: transform 0.3s ease-in-out;
    }
  </style>
</head>
<body class="bg-gray-100">
@php
  $authUser = Auth::user();
  $currentRoute = request()->route()->getName();
@endphp

  <!-- Overlay (mobile only) -->
  <div id="overlay" class="fixed inset-0 bg-black bg-opacity-50 hidden z-40 md:hidden"></div>

  <!-- Sidebar -->
  <aside id="sidebar" class="sidebar fixed top-0 left-0 h-full w-64 bg-white shadow-xl flex flex-col z-50 -translate-x-full md:translate-x-0">
    <div class="p-6 flex items-center justify-between border-b border-gray-200">
      <svg width="50" height="50" viewBox="0 0 192 192" xmlns="http://www.w3.org/2000/svg">
        <rect width="192" height="192" fill="#6BBF59" rx="20"/>
        <rect x="48" y="36" width="60" height="24" fill="white" rx="4"/>
        <circle cx="136" cy="48" r="16" fill="#FF9933"/>
        <path d="M48 84 H108 V108 H72 V132 H108 V156 H48 Z" fill="white"/>
      </svg>
      <button type="button" id="closeSidebar" onclick="var s=document.getElementById('sidebar'); var o=document.getElementById('overlay'); if(s) s.classList.add('-translate-x-full'); if(o) o.classList.add('hidden'); return false;" class="md:hidden bg-red-500 hover:bg-red-600 text-white focus:outline-none p-2.5 rounded-lg transition shadow-md hover:shadow-lg cursor-pointer" aria-label="Close sidebar">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
        </svg>
      </button>
    </div>

    <nav class="flex-1 p-6 space-y-2 overflow-y-auto">
      <a href="{{ route('employee.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg {{ $currentRoute == 'employee.dashboard' ? 'bg-brand-green text-white font-medium shadow-sm' : 'text-gray-700 hover:bg-green-50 hover:text-brand-green' }} transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        <span class="ml-3">Dashboard</span>
      </a>
      <!-- Purchase Orders Dropdown -->
      <div class="relative">
        <button id="purchaseOrdersDropdownBtn" class="w-full flex items-center px-4 py-3 rounded-lg {{ in_array($currentRoute, ['purchase-orders.index', 'purchase-orders.create']) ? 'bg-brand-green text-white font-medium shadow-sm' : 'text-gray-700 hover:bg-green-50 hover:text-brand-green' }} transition">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
          </svg>
          <span class="ml-3 flex-1 text-left">Purchase Orders</span>
          <svg id="purchaseOrdersDropdownArrow" class="w-4 h-4 transition-transform {{ in_array($currentRoute, ['purchase-orders.index', 'purchase-orders.create']) ? 'rotate-180' : '' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
          </svg>
        </button>
        <div id="purchaseOrdersDropdown" class="ml-4 mt-2 space-y-1 {{ in_array($currentRoute, ['purchase-orders.index', 'purchase-orders.create']) ? '' : 'hidden' }}">
          <a href="{{ route('purchase-orders.create') }}" class="flex items-center px-4 py-2 rounded-lg {{ $currentRoute == 'purchase-orders.create' ? 'bg-brand-green text-white' : 'text-gray-600 hover:bg-green-50 hover:text-brand-green' }} transition text-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            <span>Create Purchase</span>
          </a>
          <a href="{{ route('purchase-orders.index') }}" class="flex items-center px-4 py-2 rounded-lg {{ $currentRoute == 'purchase-orders.index' ? 'bg-brand-green text-white' : 'text-gray-600 hover:bg-green-50 hover:text-brand-green' }} transition text-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <span>View Purchase</span>
          </a>
        </div>
      </div>
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
            <span>New Customer</span>
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
    </nav>

    <form action="{{ route('logout') }}" method="POST" class="p-6 border-t border-gray-200 mt-auto">
      @csrf
      <button type="submit" class="w-full bg-brand-green text-white py-3 rounded-lg font-semibold hover:bg-orange-600 transition shadow-sm hover:shadow-md">
        Logout
      </button>
    </form>
  </aside>

  <!-- Main Content -->
  <div class="md:ml-64 min-h-screen flex flex-col">

    <!-- Navbar -->
    <header class="bg-white gradient-to-r from-pink-500 to-yellow-400 text-black shadow-md py-4 px-6 sticky top-0 z-30 md:ml-0">
      <div class="flex justify-between items-center">
        <div class="flex items-center space-x-4">
          <button type="button" id="openSidebar" class="md:hidden bg-brand-green hover:bg-green-600 text-white focus:outline-none p-2.5 rounded-lg transition shadow-lg hover:shadow-xl z-50 relative">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
            </svg>
          </button>
          <div>
            <h1 class="text-xl md:text-2xl font-bold">Customers</h1>
            <p class="text-black text-xs md:text-sm mt-1">Manage your customers</p>
          </div>
        </div>

        <div class="flex items-center space-x-3 md:space-x-4">
          <div class="text-right hidden sm:block">
            <p class="text-sm font-medium">{{ $authUser->name }}</p>
            <p class="text-xs text-black capitalize">{{ $authUser->role }}</p>
          </div>
          <img src="https://ui-avatars.com/api/?name={{ urlencode($authUser->name) }}&background=fa709a&color=fff" alt="profile" class="w-10 h-10 rounded-full border-2 border-white shadow-sm">
          @if(Auth::user()->isSuperAdmin())
            <a href="{{ route('super-admin.dashboard') }}" class="hidden md:inline px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg text-sm font-medium transition">Back to Super Admin</a>
          @elseif(Auth::user()->isAdmin())
            <a href="{{ route('admin.dashboard') }}" class="hidden md:inline px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg text-sm font-medium transition">Back to Admin</a>
          @endif
        </div>
      </div>
    </header>

    <!-- Dashboard Body -->
    <main class="flex-1 p-6 space-y-6 md:ml-0">
      @if(session('status'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-lg">
          {{ session('status') }}
        </div>
      @endif

      @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg">
          <ul class="list-disc list-inside">
            @foreach($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif

      <!-- Header with Create Button -->
      <div class="flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">All Customers</h2>
        <a href="{{ route('customers.create') }}" class="bg-brand-green text-white px-6 py-2 rounded-lg font-semibold hover:bg-green-600 transition shadow-sm hover:shadow-md flex items-center space-x-2">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          <span>Create Customer</span>
        </a>
      </div>

      <!-- Customers Content -->
      @if($customers->isEmpty())
        <div class="bg-white rounded-xl shadow-md border border-gray-100 p-8 text-center">
          <p class="text-gray-500">No customers found. <a href="{{ route('customers.create') }}" class="text-brand-green hover:text-green-600 font-medium">Create your first customer</a></p>
        </div>
      @else
        <!-- Mobile Card View -->
        <div class="block md:hidden space-y-4">
          @foreach($customers as $customer)
            <div class="bg-white border border-gray-200 rounded-lg p-4 shadow-sm hover:shadow-md transition">
              <div class="flex items-center space-x-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-brand-green flex items-center justify-center text-white font-bold text-lg flex-shrink-0">
                  {{ strtoupper(mb_substr($customer->customer_name, 0, 1)) }}
                </div>
                <div class="flex-1 min-w-0">
                  <h3 class="font-semibold text-gray-900 text-lg truncate">{{ $customer->customer_name }}</h3>
                </div>
              </div>
              
              <div class="space-y-2 border-t border-gray-100 pt-3">
                <div class="flex justify-between items-center">
                  <span class="text-sm text-gray-600">Email:</span>
                  <span class="text-sm text-gray-900 truncate ml-2">{{ $customer->email ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between items-center">
                  <span class="text-sm text-gray-600">Phone:</span>
                  <span class="text-sm text-gray-900">{{ $customer->phone ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between items-center">
                  <span class="text-sm text-gray-600">City:</span>
                  <span class="text-sm text-gray-900">{{ $customer->city ?? 'N/A' }}</span>
                </div>
                <div class="flex justify-between items-center">
                  <span class="text-sm text-gray-600">State:</span>
                  <span class="text-sm text-gray-900">{{ $customer->state ?? 'N/A' }}</span>
                </div>
                @if($customer->address)
                <div class="pt-2 border-t border-gray-100">
                  <p class="text-xs text-gray-500 mb-1">Address:</p>
                  <p class="text-sm text-gray-900">{{ $customer->address }}</p>
                </div>
                @endif
                @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                <div class="flex items-center space-x-3 pt-3 border-t border-gray-100">
                  <a href="{{ route('customers.edit', $customer) }}" class="flex-1 text-center px-4 py-2 bg-brand-green text-white rounded-lg hover:bg-green-600 transition text-sm font-medium">
                    Edit
                  </a>
                  <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="flex-1" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="w-full px-4 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition text-sm font-medium">
                      Delete
                    </button>
                  </form>
                </div>
                @endif
              </div>
            </div>
          @endforeach
        </div>

        <!-- Desktop Table View -->
        <div class="hidden md:block bg-white rounded-xl shadow-md border border-gray-100 overflow-hidden">
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead class="bg-gray-50 border-b border-gray-200">
                <tr>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Customer Name</th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Email</th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Phone</th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">City</th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">State</th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Actions</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                @foreach($customers as $customer)
                  <tr class="hover:bg-gray-50 transition">
                    <td class="px-6 py-4 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="w-10 h-10 rounded-full bg-brand-green flex items-center justify-center text-white font-bold text-sm mr-3">
                          {{ strtoupper(mb_substr($customer->customer_name, 0, 1)) }}
                        </div>
                        <div class="text-sm font-medium text-gray-900">{{ $customer->customer_name }}</div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $customer->email ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $customer->phone ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $customer->city ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $customer->state ?? 'N/A' }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                      @if(Auth::user()->isSuperAdmin() || Auth::user()->isAdmin())
                      <div class="flex items-center space-x-2">
                        <a href="{{ route('customers.edit', $customer) }}" class="text-brand-green hover:text-green-600">Edit</a>
                        <form action="{{ route('customers.destroy', $customer) }}" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this customer?');">
                          @csrf
                          @method('DELETE')
                          <button type="submit" class="text-red-600 hover:text-red-800">Delete</button>
                        </form>
                      </div>
                      @else
                      <span class="text-gray-400">-</span>
                      @endif
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
        </div>
      @endif

    </main>
  </div>
  <script>
    // Sidebar toggle for mobile - Direct implementation
    (function() {
      'use strict';
      
      function getElements() {
        return {
          sidebar: document.getElementById('sidebar'),
          overlay: document.getElementById('overlay'),
          openSidebar: document.getElementById('openSidebar'),
          closeSidebar: document.getElementById('closeSidebar')
        };
      }
      
      function openSidebar() {
        const elements = getElements();
        if (elements.sidebar) {
          elements.sidebar.classList.remove('-translate-x-full');
        }
        if (elements.overlay) {
          elements.overlay.classList.remove('hidden');
        }
      }
      
      function closeSidebar() {
        const elements = getElements();
        if (elements.sidebar) {
          elements.sidebar.classList.add('-translate-x-full');
        }
        if (elements.overlay) {
          elements.overlay.classList.add('hidden');
        }
      }
      
      function initSidebar() {
        const elements = getElements();
        
        // Open sidebar button
        if (elements.openSidebar) {
          elements.openSidebar.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            openSidebar();
            return false;
          };
        }
        
        // Close sidebar button - Multiple event handlers for reliability
        if (elements.closeSidebar) {
          // Click event
          elements.closeSidebar.onclick = function(e) {
            e.preventDefault();
            e.stopPropagation();
            closeSidebar();
            return false;
          };
          
          // Touch event for mobile
          elements.closeSidebar.ontouchend = function(e) {
            e.preventDefault();
            e.stopPropagation();
            closeSidebar();
            return false;
          };
          
          // Also add event listener as backup
          elements.closeSidebar.addEventListener('click', function(e) {
            e.preventDefault();
            e.stopPropagation();
            closeSidebar();
          }, true);
        }
        
        // Close on overlay click
        if (elements.overlay) {
          elements.overlay.onclick = function(e) {
            e.preventDefault();
            closeSidebar();
          };
        }
      }
      
      // Initialize immediately and also on DOM ready
      if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initSidebar);
      } else {
        initSidebar();
      }
      
      // Also try after a small delay to ensure elements exist
      setTimeout(initSidebar, 100);
    })();

    // Purchase Orders Dropdown Toggle
    const purchaseOrdersDropdownBtn = document.getElementById('purchaseOrdersDropdownBtn');
    const purchaseOrdersDropdown = document.getElementById('purchaseOrdersDropdown');
    const purchaseOrdersDropdownArrow = document.getElementById('purchaseOrdersDropdownArrow');

    if (purchaseOrdersDropdownBtn && purchaseOrdersDropdown) {
      // Auto-open dropdown if on purchase orders pages
      @if(in_array(request()->route()->getName(), ['purchase-orders.index', 'purchase-orders.create']))
        purchaseOrdersDropdown.classList.remove('hidden');
      @endif

      purchaseOrdersDropdownBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        purchaseOrdersDropdown.classList.toggle('hidden');
        if (purchaseOrdersDropdownArrow) {
          purchaseOrdersDropdownArrow.classList.toggle('rotate-180');
        }
      });
    }

    // Customers Dropdown Toggle
    const customersDropdownBtn = document.getElementById('customersDropdownBtn');
    const customersDropdown = document.getElementById('customersDropdown');
    const customersDropdownArrow = document.getElementById('customersDropdownArrow');

    if (customersDropdownBtn && customersDropdown) {
      // Auto-open dropdown if on customers pages
      @if(in_array(request()->route()->getName(), ['customers.index', 'customers.create', 'customers.edit']))
        customersDropdown.classList.remove('hidden');
      @endif

      customersDropdownBtn.addEventListener('click', function(e) {
        e.preventDefault();
        e.stopPropagation();
        customersDropdown.classList.toggle('hidden');
        if (customersDropdownArrow) {
          customersDropdownArrow.classList.toggle('rotate-180');
        }
      });
    }
  </script>
</body>
</html>

