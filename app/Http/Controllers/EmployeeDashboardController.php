<?php

namespace App\Http\Controllers;

use App\Models\PurchaseOrder;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EmployeeDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!($user && ($user->isSuperAdmin() || $user->isAdmin() || $user->isManager() || $user->isEmployee()))) {
            return redirect()->route('login')->withErrors(['error' => 'Unauthorized access']);
        }
        $totalPurchaseOrders = PurchaseOrder::count();
        $recentOrdersCount = PurchaseOrder::where('created_at', '>=', now()->subDays(30))->count();
        $orderTrackingInTransitCount = PurchaseOrder::where('status', 'in_transit')->count();
        $activeCustomersCount = PurchaseOrder::where('created_at', '>=', now()->subDays(30))
            ->distinct('customer_name')
            ->count('customer_name');

        $recentPurchaseOrders = PurchaseOrder::latest()->take(3)->get();

        $customerTracking = PurchaseOrder::selectRaw('customer_name, COUNT(*) as orders_count, SUM(quantity * product_price) as total_value, MAX(created_at) as last_order_at')
            ->groupBy('customer_name')
            ->orderByDesc('total_value')
            ->take(3)
            ->get();

        return view('dashboards.employee', compact(
            'totalPurchaseOrders',
            'recentOrdersCount',
            'orderTrackingInTransitCount',
            'activeCustomersCount',
            'recentPurchaseOrders',
            'customerTracking'
        ));
    }

    public function create()
    {
        $user = Auth::user();
        if (!($user && ($user->isSuperAdmin() || $user->isAdmin() || $user->isManager() || $user->isEmployee()))) {
            return redirect()->route('login')->withErrors(['error' => 'Unauthorized access']);
        }

        $products = Product::orderBy('product_name')->get();
        $customers = Customer::orderBy('customer_name')->get();
        $poNumber = $this->generatePONumber();

        return view('dashboards.create-purchase-order', compact('products', 'customers', 'poNumber'));
    }

    public function store(Request $request)
    {
        // Validate common fields
        $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'po_number' => ['required', 'string', 'max:255'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.product_name' => ['required', 'string', 'max:255'],
            'items.*.sku_number' => ['required', 'string', 'max:255'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
            'items.*.product_price' => ['required', 'numeric', 'min:0'],
            'items.*.delivery_date' => ['required', 'date'],
        ]);

        $customerName = $request->customer_name;
        $poNumber = $request->po_number;
        $items = $request->items;
        $createdCount = 0;

        // Create purchase order for each item
        foreach ($items as $item) {
            PurchaseOrder::create([
                'customer_name' => $customerName,
                'product_name' => $item['product_name'],
                'po_number' => $poNumber,
                'sku_number' => $item['sku_number'],
                'quantity' => $item['quantity'],
                'product_price' => $item['product_price'],
                'delivery_date' => $item['delivery_date'],
                'status' => 'pending',
            ]);
            $createdCount++;
        }

        $message = $createdCount > 1 
            ? "Purchase order created successfully with {$createdCount} items." 
            : 'Purchase order created successfully.';

        return redirect()->route('purchase-orders.create')->with('status', $message);
    }

    public function allOrders()
    {
        $user = Auth::user();
        // Allow super admin or admin to access
        if (!($user && ($user->isSuperAdmin() || $user->isAdmin()))) {
            return redirect()->route('login')->withErrors(['error' => 'Unauthorized access']);
        }

        $allPurchaseOrders = PurchaseOrder::orderByDesc('created_at')->get();

        return view('dashboards.all-orders', compact('allPurchaseOrders'));
    }

    public function updateStatus(Request $request, PurchaseOrder $purchaseOrder)
    {
        $user = Auth::user();
        // Allow super admin or admin to update order status
        if (!($user && ($user->isSuperAdmin() || $user->isAdmin()))) {
            return redirect()->route('admin.dashboard')->withErrors(['error' => 'Unauthorized access']);
        }

        $validated = $request->validate([
            'status' => ['required', 'string', 'in:pending,in_transit,delivered'],
        ]);

        $purchaseOrder->update(['status' => $validated['status']]);

        // Redirect back to the page where the update was made
        $referer = $request->headers->get('referer');
        if ($referer && str_contains($referer, 'all-orders')) {
            return redirect()->route('admin.all-orders')->with('status', 'Order status updated successfully.');
        }
        if ($referer && str_contains($referer, 'purchase-orders')) {
            return redirect()->route('purchase-orders.index')->with('status', 'Order status updated successfully.');
        }

        return redirect()->route('admin.dashboard')->with('status', 'Order status updated successfully.');
    }

    public function storeProduct(Request $request)
    {
        $user = Auth::user();
        // Only allow super admin or admin to add products
        if (!($user && ($user->isSuperAdmin() || $user->isAdmin()))) {
            return redirect()->route('employee.dashboard')->withErrors(['error' => 'Unauthorized access']);
        }

        $validated = $request->validate([
            'product_name' => ['required', 'string', 'max:255'],
            'sku_code' => ['required', 'string', 'max:255', 'unique:products,sku_code'],
            'product_rate' => ['required', 'numeric', 'min:0'],
        ]);

        Product::create($validated);

        return redirect()->route('purchase-orders.create')->with('status', 'Product added successfully.');
    }

    public function purchaseOrders(Request $request)
    {
        $user = Auth::user();
        if (!($user && ($user->isSuperAdmin() || $user->isAdmin() || $user->isManager() || $user->isEmployee()))) {
            return redirect()->route('login')->withErrors(['error' => 'Unauthorized access']);
        }

        $query = PurchaseOrder::query();

        // Search functionality
        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('po_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('product_name', 'like', "%{$search}%")
                  ->orWhere('sku_number', 'like', "%{$search}%");
            });
        }

        // Pagination with 50 items per page
        $purchaseOrders = $query->orderByDesc('created_at')->paginate(50)->withQueryString();

        return view('dashboards.purchase-orders', compact('purchaseOrders'));
    }

    /**
     * Generate next Purchase Order Number
     * Format: PO-YYYY-XXXX (e.g., PO-2024-0001)
     */
    private function generatePONumber(): string
    {
        $year = date('Y');
        $prefix = "PO-{$year}-";

        // Get the last PO number for this year
        $lastPO = PurchaseOrder::where('po_number', 'like', "{$prefix}%")
            ->orderBy('po_number', 'desc')
            ->first();

        if ($lastPO) {
            // Extract the number part from last PO (e.g., "PO-2024-0001" -> "0001")
            $lastNumber = (int) substr($lastPO->po_number, strlen($prefix));
            $nextNumber = $lastNumber + 1;
        } else {
            // First PO of the year
            $nextNumber = 1;
        }

        // Format with leading zeros (4 digits)
        $formattedNumber = str_pad($nextNumber, 4, '0', STR_PAD_LEFT);

        return "{$prefix}{$formattedNumber}";
    }
}