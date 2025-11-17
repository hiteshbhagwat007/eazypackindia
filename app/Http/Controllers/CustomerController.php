<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CustomerController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        if (!($user && ($user->isSuperAdmin() || $user->isAdmin() || $user->isManager() || $user->isEmployee()))) {
            return redirect()->route('login')->withErrors(['error' => 'Unauthorized access']);
        }

        $customers = Customer::orderBy('customer_name')->get();

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        $user = Auth::user();
        if (!($user && ($user->isSuperAdmin() || $user->isAdmin() || $user->isManager() || $user->isEmployee()))) {
            return redirect()->route('login')->withErrors(['error' => 'Unauthorized access']);
        }

        return view('customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'pincode' => ['nullable', 'string', 'max:10'],
        ]);

        Customer::create($validated);

        return redirect()->route('customers.index')->with('status', 'Customer created successfully.');
    }

    public function edit(Customer $customer)
    {
        $user = Auth::user();
        if (!($user && ($user->isSuperAdmin() || $user->isAdmin() || $user->isManager() || $user->isEmployee()))) {
            return redirect()->route('login')->withErrors(['error' => 'Unauthorized access']);
        }

        return view('customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'customer_name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:20'],
            'address' => ['nullable', 'string'],
            'city' => ['nullable', 'string', 'max:255'],
            'state' => ['nullable', 'string', 'max:255'],
            'pincode' => ['nullable', 'string', 'max:10'],
        ]);

        $customer->update($validated);

        return redirect()->route('customers.index')->with('status', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        $user = Auth::user();
        if (!($user && ($user->isSuperAdmin() || $user->isAdmin()))) {
            return redirect()->route('customers.index')->withErrors(['error' => 'Unauthorized access']);
        }

        $customer->delete();

        return redirect()->route('customers.index')->with('status', 'Customer deleted successfully.');
    }
}

