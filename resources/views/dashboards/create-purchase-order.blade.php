<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Create Purchase Order - ERP CRM</title>
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
            <h1 class="text-xl md:text-2xl font-bold">Create Purchase Order</h1>
            <p class="text-black text-xs md:text-sm mt-1">Create a new purchase order</p>
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
    <main class="flex-1 p-4 sm:p-6 space-y-4 sm:space-y-6 md:ml-0">
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

      <!-- Add Product Form (Admin Only) -->
      @if($authUser->isSuperAdmin() || $authUser->isAdmin())
      <div class="bg-white p-4 sm:p-6 rounded-xl shadow-md border border-gray-100">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-4 sm:mb-6">
          <div>
            <h2 class="text-lg sm:text-xl font-semibold text-gray-800">Add Product</h2>
            <p class="text-xs sm:text-sm text-gray-500">Add new products to the system (Admin Only)</p>
          </div>
          <button id="toggleProductForm" class="w-full sm:w-auto px-4 py-2 bg-brand-green text-white rounded-lg hover:bg-green-600 transition shadow-sm hover:shadow-md font-medium flex items-center justify-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
            </svg>
            <span id="toggleProductFormLabel">Add Product</span>
          </button>
        </div>

        <form id="productForm" class="hidden space-y-4" method="POST" action="{{ route('products.store') }}">
          @csrf
          <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Product Name *</label>
              <input type="text" name="product_name" value="{{ old('product_name') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent" placeholder="Enter product name" required>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">SKU Code *</label>
              <input type="text" name="sku_code" value="{{ old('sku_code') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent" placeholder="SKU-XXXXX" required>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Product Rate (₹) *</label>
              <input type="number" name="product_rate" value="{{ old('product_rate') }}" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent" placeholder="0.00" step="0.01" min="0" required>
            </div>
          </div>

          <div class="flex flex-col sm:flex-row justify-end gap-3 sm:gap-4 pt-4">
            <button type="button" id="cancelProductForm" class="w-full sm:w-auto px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium">
              Cancel
            </button>
            <button type="submit" class="w-full sm:w-auto px-6 py-2 bg-brand-green text-white rounded-lg hover:bg-green-600 transition shadow-sm hover:shadow-md font-medium">
              Add Product
            </button>
          </div>
        </form>
      </div>
      @endif

      <!-- Purchase Order Form -->
      <div class="bg-white p-4 sm:p-6 rounded-xl shadow-md border border-gray-100">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0 mb-4 sm:mb-6">
          <h2 class="text-lg sm:text-xl font-semibold text-gray-800">Create New Purchase Order</h2>
          <button onclick="document.getElementById('purchaseOrderForm').reset()" class="text-sm text-gray-600 hover:text-brand-green transition">
            Clear Form
          </button>
        </div>
        <form id="purchaseOrderForm" action="{{ route('purchase-orders.store') }}" method="POST" class="space-y-6">
          @csrf
          
          <!-- Common Fields -->
          <div class="bg-gray-50 p-3 sm:p-4 rounded-lg border border-gray-200">
            <h3 class="text-base sm:text-lg font-semibold text-gray-800 mb-3 sm:mb-4">Order Information</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Customer Name *</label>
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                  <select id="customerName" name="customer_name" class="flex-1 px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent bg-white" required>
                    <option value="">-- Select a Customer --</option>
                    @foreach($customers ?? [] as $customer)
                      <option value="{{ $customer->customer_name }}" {{ old('customer_name') == $customer->customer_name ? 'selected' : '' }}>
                        {{ $customer->customer_name }}
                        @if($customer->email)
                          ({{ $customer->email }})
                        @endif
                      </option>
                    @endforeach
                  </select>
                  <a href="{{ route('customers.create') }}" class="px-3 py-2 bg-brand-green text-white rounded-lg hover:bg-green-600 transition shadow-sm hover:shadow-md flex items-center justify-center space-x-1 whitespace-nowrap text-sm" title="Create New Customer">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                    </svg>
                    <span class="hidden sm:inline">New</span>
                    <span class="sm:hidden">Add</span>
                  </a>
                </div>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Purchase Order No. *</label>
                <div class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                  <input type="text" id="poNumber" name="po_number" value="{{ old('po_number', $poNumber ?? '') }}" class="flex-1 px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent bg-gray-50" readonly required>
                  <button type="button" id="regeneratePOBtn" class="px-3 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition shadow-sm hover:shadow-md flex items-center justify-center space-x-1 whitespace-nowrap text-sm" title="Generate New PO Number">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span class="hidden sm:inline">Refresh</span>
                    <span class="sm:hidden">↻</span>
                  </button>
                </div>
                <p class="text-xs text-gray-500 mt-1">Auto-generated PO number</p>
              </div>
            </div>
          </div>

          <!-- Items Section -->
          <div class="bg-white p-3 sm:p-4 rounded-lg border border-gray-200">
            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-3 sm:gap-0 mb-4">
              <h3 class="text-base sm:text-lg font-semibold text-gray-800">Order Items</h3>
              <button type="button" id="addItemBtn" class="w-full sm:w-auto px-4 py-2 bg-brand-green text-white rounded-lg hover:bg-green-600 transition shadow-sm hover:shadow-md flex items-center justify-center space-x-2 text-sm sm:text-base">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
                </svg>
                <span>Add Item</span>
              </button>
            </div>

            <!-- Items Container -->
            <div id="itemsContainer" class="space-y-4">
              <!-- First Item -->
              <div class="item-row border border-gray-200 rounded-lg p-3 sm:p-4 bg-gray-50" data-item-index="0">
                <div class="flex justify-between items-center mb-3">
                  <h4 class="text-sm font-semibold text-gray-700">Item #1</h4>
                  <button type="button" class="remove-item-btn text-red-600 hover:text-red-800 text-sm font-medium hidden">
                    Remove
                  </button>
                </div>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Product *</label>
                    <select class="item-product-select w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent bg-white" required>
                      <option value="">-- Select Product --</option>
                      @foreach($products ?? [] as $product)
                        <option value="{{ $product->id }}" 
                          data-product-name="{{ $product->product_name }}"
                          data-sku-code="{{ $product->sku_code }}"
                          data-product-rate="{{ $product->product_rate }}">
                          {{ $product->product_name }} | SKU: {{ $product->sku_code }} | ₹{{ number_format($product->product_rate, 2) }}
                        </option>
                      @endforeach
                    </select>
                    <input type="hidden" class="item-product-name" name="items[0][product_name]" required>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">SKU Number *</label>
                    <input type="text" class="item-sku-number w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent bg-gray-50" name="items[0][sku_number]" readonly required>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
                    <input type="number" class="item-quantity w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent" name="items[0][quantity]" placeholder="0" min="1" required>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Price (₹) *</label>
                    <input type="number" class="item-price w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent bg-gray-50" name="items[0][product_price]" step="0.01" min="0" readonly required>
                  </div>
                </div>
                <div class="mt-4">
                  <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Date *</label>
                  <input type="date" class="item-delivery-date w-full sm:w-auto px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent" name="items[0][delivery_date]" required>
                </div>
              </div>
            </div>
          </div>

          <div class="flex flex-col sm:flex-row justify-end gap-3 sm:gap-4 pt-4">
            <a href="{{ route('employee.dashboard') }}" class="w-full sm:w-auto px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium text-center">
              Cancel
            </a>
            <button type="submit" id="createOrderBtn" class="w-full sm:w-auto px-6 py-2 bg-brand-green text-white rounded-lg hover:bg-green-600 transition shadow-sm hover:shadow-md font-medium flex items-center justify-center space-x-2 disabled:opacity-50 disabled:cursor-not-allowed">
              <span id="createOrderBtnText">Create Order</span>
              <svg id="createOrderBtnSpinner" class="hidden animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </button>
          </div>
        </form>
      </div>
    </main>
  </div>
  <script>
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

    // Multiple Items Management
    let itemIndex = 1;
    const itemsContainer = document.getElementById('itemsContainer');
    const addItemBtn = document.getElementById('addItemBtn');
    const products = @json($products ?? []);

    // Add Item Function
    if (addItemBtn && itemsContainer) {
      addItemBtn.addEventListener('click', function() {
        const itemRow = document.createElement('div');
        itemRow.className = 'item-row border border-gray-200 rounded-lg p-3 sm:p-4 bg-gray-50';
        itemRow.setAttribute('data-item-index', itemIndex);
        
        const itemNumber = itemIndex + 1;
        itemRow.innerHTML = `
          <div class="flex justify-between items-center mb-3">
            <h4 class="text-sm font-semibold text-gray-700">Item #${itemNumber}</h4>
            <button type="button" class="remove-item-btn text-red-600 hover:text-red-800 text-sm font-medium">
              Remove
            </button>
          </div>
          <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Product *</label>
              <select class="item-product-select w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent bg-white" required>
                <option value="">-- Select Product --</option>
                ${products.map(product => `
                  <option value="${product.id}" 
                    data-product-name="${product.product_name}"
                    data-sku-code="${product.sku_code}"
                    data-product-rate="${product.product_rate}">
                    ${product.product_name} | SKU: ${product.sku_code} | ₹${parseFloat(product.product_rate).toFixed(2)}
                  </option>
                `).join('')}
              </select>
              <input type="hidden" class="item-product-name" name="items[${itemIndex}][product_name]" required>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">SKU Number *</label>
              <input type="text" class="item-sku-number w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent bg-gray-50" name="items[${itemIndex}][sku_number]" readonly required>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Quantity *</label>
              <input type="number" class="item-quantity w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent" name="items[${itemIndex}][quantity]" placeholder="0" min="1" required>
            </div>
            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Price (₹) *</label>
              <input type="number" class="item-price w-full px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent bg-gray-50" name="items[${itemIndex}][product_price]" step="0.01" min="0" readonly required>
            </div>
          </div>
          <div class="mt-4">
            <label class="block text-sm font-medium text-gray-700 mb-2">Delivery Date *</label>
            <input type="date" class="item-delivery-date w-full sm:w-auto px-3 sm:px-4 py-2 text-sm sm:text-base border border-gray-300 rounded-lg focus:ring-2 focus:ring-brand-green focus:border-transparent" name="items[${itemIndex}][delivery_date]" required>
          </div>
        `;
        
        itemsContainer.appendChild(itemRow);
        
        // Attach event listeners to new item
        attachItemEventListeners(itemRow);
        
        // Show remove button on first item if more than one item
        updateRemoveButtons();
        
        itemIndex++;
      });
    }

    // Remove Item Function
    if (itemsContainer) {
      itemsContainer.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-item-btn')) {
          const itemRow = e.target.closest('.item-row');
          if (itemRow && itemsContainer.children.length > 1) {
            itemRow.remove();
            updateRemoveButtons();
            updateItemNumbers();
          }
        }
      });
    }

    // Product Select Change Handler
    function attachItemEventListeners(itemRow) {
      const productSelect = itemRow.querySelector('.item-product-select');
      const productNameInput = itemRow.querySelector('.item-product-name');
      const skuNumberInput = itemRow.querySelector('.item-sku-number');
      const productPriceInput = itemRow.querySelector('.item-price');

      if (productSelect && productNameInput && skuNumberInput && productPriceInput) {
        productSelect.addEventListener('change', function() {
          const selectedOption = this.options[this.selectedIndex];
          if (selectedOption.value) {
            productNameInput.value = selectedOption.getAttribute('data-product-name');
            skuNumberInput.value = selectedOption.getAttribute('data-sku-code');
            productPriceInput.value = selectedOption.getAttribute('data-product-rate');
          } else {
            productNameInput.value = '';
            skuNumberInput.value = '';
            productPriceInput.value = '';
          }
        });
      }
    }

    // Update Remove Buttons Visibility
    function updateRemoveButtons() {
      const itemRows = document.querySelectorAll('.item-row');
      itemRows.forEach((row, index) => {
        const removeBtn = row.querySelector('.remove-item-btn');
        if (removeBtn) {
          if (itemRows.length > 1) {
            removeBtn.classList.remove('hidden');
          } else {
            removeBtn.classList.add('hidden');
          }
        }
      });
    }

    // Update Item Numbers
    function updateItemNumbers() {
      const itemRows = document.querySelectorAll('.item-row');
      itemRows.forEach((row, index) => {
        const itemNumber = row.querySelector('h4');
        if (itemNumber) {
          itemNumber.textContent = `Item #${index + 1}`;
        }
      });
    }

    // Attach event listeners to first item
    const firstItem = document.querySelector('.item-row');
    if (firstItem) {
      attachItemEventListeners(firstItem);
    }

    // Regenerate PO Number
    const regeneratePOBtn = document.getElementById('regeneratePOBtn');
    if (regeneratePOBtn) {
      regeneratePOBtn.addEventListener('click', function() {
        // Reload page to generate new PO number
        window.location.reload();
      });
    }

    // Form Submission Loading State
    const purchaseOrderForm = document.getElementById('purchaseOrderForm');
    const createOrderBtn = document.getElementById('createOrderBtn');
    const createOrderBtnText = document.getElementById('createOrderBtnText');
    const createOrderBtnSpinner = document.getElementById('createOrderBtnSpinner');

    if (purchaseOrderForm && createOrderBtn) {
      purchaseOrderForm.addEventListener('submit', function(e) {
        // Show loading state
        createOrderBtn.disabled = true;
        createOrderBtnText.textContent = 'Creating Order...';
        createOrderBtnSpinner.classList.remove('hidden');
      });
    }
  </script>
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

