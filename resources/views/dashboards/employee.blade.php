<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Employee Dashboard - Purchase Orders</title>
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
      <button id="closeSidebar" class="md:hidden text-gray-600 hover:text-gray-900 text-2xl focus:outline-none">✕</button>
    </div>

    <nav class="flex-1 p-6 space-y-2 overflow-y-auto">
      <a href="{{ route('employee.dashboard') }}" class="flex items-center px-4 py-3 rounded-lg bg-brand-green text-white font-medium shadow-sm hover:shadow-md transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
        </svg>
        <span class="ml-3">Dashboard</span>
      </a>
      <a href="{{ route('purchase-orders.create') }}" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-green-50 hover:text-brand-green transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
        </svg>
        <span class="ml-3">Create Order</span>
      </a>
      <a href="{{ route('purchase-orders.index') }}" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-green-50 hover:text-brand-green transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
        </svg>
        <span class="ml-3">Purchase Orders</span>
      </a>
      <!-- Customers Dropdown -->
      <div class="relative">
        <button id="customersDropdownBtn" class="w-full flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-green-50 hover:text-brand-green transition">
          <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
          </svg>
          <span class="ml-3 flex-1 text-left">Customers</span>
          <svg id="customersDropdownArrow" class="w-4 h-4 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
          </svg>
        </button>
        <div id="customersDropdown" class="hidden ml-4 mt-2 space-y-1">
          <a href="{{ route('customers.create') }}" class="flex items-center px-4 py-2 rounded-lg text-gray-600 hover:bg-green-50 hover:text-brand-green transition text-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
            </svg>
            <span>New Customer</span>
          </a>
          <a href="{{ route('customers.index') }}" class="flex items-center px-4 py-2 rounded-lg text-gray-600 hover:bg-green-50 hover:text-brand-green transition text-sm">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
            </svg>
            <span>View Customer</span>
          </a>
        </div>
      </div>
      <!-- <a href="#" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-green-50 hover:text-brand-green transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        <span class="ml-3">Reports</span>
      </a>
      <a href="#" class="flex items-center px-4 py-3 rounded-lg text-gray-700 hover:bg-green-50 hover:text-brand-green transition">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
        </svg>
        <span class="ml-3">Settings</span>
      </a> -->
    </nav>

    <form action="{{ route('logout') }}" method="POST" class="p-6 border-t border-gray-200 mt-auto">
    @csrf
    <button type="submit"
        class="w-full bg-brand-green text-white py-3 rounded-lg font-semibold hover:bg-orange-600 transition shadow-sm hover:shadow-md">
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
            <h1 class="text-xl md:text-2xl font-bold">Employee Dashboard</h1>
            <p class="text-black text-xs md:text-sm mt-1">Employee access panel</p>
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
          <!-- <form action="{{ route('logout') }}" method="POST" class="hidden md:inline">
            @csrf
            <button type="submit" class="px-4 py-2 bg-white/20 hover:bg-white/30 text-white rounded-lg text-sm font-medium transition">Logout</button>
          </form> -->
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
      <!-- Stats Section -->
      <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1 border border-gray-100">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-sm text-gray-500 font-medium">Total Purchase Orders</h3>
              <p class="text-3xl font-bold text-gray-800 mt-2">{{ $totalPurchaseOrders ?? 0 }}</p>
              <p class="text-xs text-green-600 mt-1">↑ 18% from last month</p>
            </div>
            <div class="bg-green-100 p-3 rounded-lg">
              <svg class="w-8 h-8 text-brand-green" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
              </svg>
            </div>
          </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1 border border-gray-100">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-sm text-gray-500 font-medium">Recent Orders</h3>
              <p class="text-3xl font-bold text-gray-800 mt-2">{{ $recentOrdersCount ?? 0 }}</p>
              <p class="text-xs text-blue-600 mt-1">Last 30 days</p>
            </div>
            <div class="bg-blue-100 p-3 rounded-lg">
              <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
              </svg>
            </div>
          </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1 border border-gray-100">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-sm text-gray-500 font-medium">Order Tracking</h3>
              <p class="text-3xl font-bold text-gray-800 mt-2">{{ $orderTrackingInTransitCount ?? 0 }}</p>
              <p class="text-xs text-orange-600 mt-1">In transit</p>
            </div>
            <div class="bg-orange-100 p-3 rounded-lg">
              <svg class="w-8 h-8 text-brand-orange" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"/>
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"/>
              </svg>
            </div>
          </div>
        </div>
        
        <div class="bg-white p-6 rounded-xl shadow-md hover:shadow-xl transition transform hover:-translate-y-1 border border-gray-100">
          <div class="flex items-center justify-between">
            <div>
              <h3 class="text-sm text-gray-500 font-medium">Active Customers</h3>
              <p class="text-3xl font-bold text-gray-800 mt-2">{{ $activeCustomersCount ?? 0 }}</p>
              <p class="text-xs text-green-600 mt-1">↑ 5% from last month</p>
            </div>
            <div class="bg-purple-100 p-3 rounded-lg">
              <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
              </svg>
            </div>
          </div>
        </div>
      </div>

    

      <!-- Recent Purchase Orders & Customer Tracking -->
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">
        <!-- Recent Purchase Orders -->
        <div class="bg-white p-6 rounded-xl shadow-md border border-gray-100">
          <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-semibold text-gray-800">Recent Purchase Orders</h2>
            <a href="{{ route('purchase-orders.index') }}" class="text-sm text-brand-green hover:text-green-600 font-medium">View All</a>
          </div>
          <div class="space-y-4">
            @forelse(($recentPurchaseOrders ?? []) as $po)
              @php
                $status = strtolower($po->status);
                $border = $status === 'delivered' ? 'border-brand-green' : ($status === 'in_transit' ? 'border-blue-500' : 'border-orange-500');
                $bg = $status === 'delivered' ? 'bg-green-50' : ($status === 'in_transit' ? 'bg-blue-50' : 'bg-orange-50');
                $chipBg = $status === 'delivered' ? 'bg-green-100' : ($status === 'in_transit' ? 'bg-blue-100' : 'bg-orange-100');
                $chipText = $status === 'delivered' ? 'text-green-700' : ($status === 'in_transit' ? 'text-blue-700' : 'text-orange-700');
                $label = $status === 'delivered' ? 'Delivered' : ($status === 'in_transit' ? 'In Transit' : 'Pending');
              @endphp
              <div class="border-l-4 {{ $border }} pl-4 py-3 {{ $bg }} rounded-r-lg hover:shadow-md transition cursor-pointer">
                <div class="flex justify-between items-start">
                  <div>
                    <p class="font-semibold text-gray-800">{{ $po->po_number }}</p>
                    <p class="text-sm text-gray-600">{{ $po->customer_name }} - {{ $po->product_name }}</p>
                    <p class="text-xs text-gray-500 mt-1">SKU: {{ $po->sku_number }} | Qty: {{ $po->quantity }} | ₹{{ number_format($po->total_amount, 2) }}</p>
                  </div>
                  <span class="px-3 py-1 text-xs rounded-full {{ $chipBg }} {{ $chipText }} font-medium">{{ $label }}</span>
                </div>
              </div>
            @empty
              <p class="text-sm text-gray-500">No recent purchase orders found.</p>
            @endforelse
          </div>
        </div>

        <!-- Customer Tracking -->
        <div class="bg-white p-4 sm:p-6 rounded-xl shadow-md border border-gray-100">
          <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 gap-3 sm:gap-0">
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800">Customer Tracking</h2>
            <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2 sm:space-x-3 sm:gap-0">
              <a href="{{ route('customers.create') }}" class="text-xs sm:text-sm bg-brand-green text-white px-3 sm:px-4 py-2 rounded-lg hover:bg-green-600 font-medium transition shadow-sm hover:shadow-md flex items-center justify-center space-x-1">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span>Create Customer</span>
              </a>
              <a href="{{ route('customers.index') }}" class="text-xs sm:text-sm text-brand-green hover:text-green-600 font-medium text-center sm:text-left">View All</a>
            </div>
          </div>
          <div class="space-y-3 sm:space-y-4">
            @forelse(($customerTracking ?? []) as $cust)
              @php
                $name = trim($cust->customer_name ?? '');
                $parts = preg_split('/\s+/', $name);
                $first = isset($parts[0]) ? mb_substr($parts[0], 0, 1) : '';
                $second = isset($parts[1]) ? mb_substr($parts[1], 0, 1) : '';
                $initials = strtoupper($first . $second);
                $colorClass = 'bg-brand-green';
              @endphp
              <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between p-3 sm:p-4 bg-gray-50 rounded-lg hover:bg-gray-100 transition cursor-pointer gap-3 sm:gap-0">
                <div class="flex items-center space-x-3 sm:space-x-4 flex-1 min-w-0">
                  <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-full {{ $colorClass }} flex items-center justify-center text-white font-bold text-base sm:text-lg flex-shrink-0">
                    {{ $initials }}
                  </div>
                  <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm sm:text-base truncate">{{ $cust->customer_name }}</p>
                    <p class="text-xs text-gray-500 mt-0.5 sm:mt-0">{{ $cust->orders_count }} orders | Last: {{ \Carbon\Carbon::parse($cust->last_order_at)->diffForHumans() }}</p>
                  </div>
                </div>
                <div class="text-left sm:text-right flex-shrink-0 sm:ml-4">
                  <p class="text-xs sm:text-sm font-semibold text-gray-800">₹{{ number_format($cust->total_value, 2) }}</p>
                  <p class="text-xs text-gray-500">Total value</p>
                </div>
              </div>
            @empty
              <p class="text-sm text-gray-500">No customer data available.</p>
            @endforelse
          </div>
        </div>
      </div>

    </main>
  </div>
  <script>
    // Sidebar toggle for mobile
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

    // Product Form Toggle (Admin Only)
    @if($authUser->isSuperAdmin() || $authUser->isAdmin())
    const toggleProductFormBtn = document.getElementById('toggleProductForm');
    const toggleProductFormLabel = document.getElementById('toggleProductFormLabel');
    const productForm = document.getElementById('productForm');
    const cancelProductFormBtn = document.getElementById('cancelProductForm');

    if (toggleProductFormBtn && productForm) {
      const showProductForm = () => {
        productForm.classList.remove('hidden');
        toggleProductFormLabel.textContent = 'Hide Form';
      };

      const hideProductForm = () => {
        productForm.classList.add('hidden');
        toggleProductFormLabel.textContent = 'Add Product';
      };

      toggleProductFormBtn.addEventListener('click', () => {
        productForm.classList.contains('hidden') ? showProductForm() : hideProductForm();
      });

      if (cancelProductFormBtn) {
        cancelProductFormBtn.addEventListener('click', hideProductForm);
      }
    }
    @endif

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
</body>
</html>