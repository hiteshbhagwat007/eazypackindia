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
    <button type="submit" class="w-full bg-brand-green text-white py-3 rounded-lg font-semibold hover:bg-orange-600 transition shadow-sm hover:shadow-md">
      Logout
    </button>
  </form>
</aside>

