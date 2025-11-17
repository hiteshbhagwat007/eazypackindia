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

