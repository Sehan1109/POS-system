<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Customer;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::latest()->paginate(15);
        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'phone'        => 'nullable|string|max:30',
            'email'        => 'nullable|email|max:255',
            'credit_limit' => 'nullable|numeric|min:0',
        ]);
        $validated['credit_limit'] = $validated['credit_limit'] ?? 0;
        $customer = Customer::create($validated);
        ActivityLog::record('created', "Created customer: {$customer->name}", $customer);
        return redirect()->route('admin.customers.index')->with('success', 'Customer created successfully.');
    }

    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $validated = $request->validate([
            'name'         => 'required|string|max:255',
            'phone'        => 'nullable|string|max:30',
            'email'        => 'nullable|email|max:255',
            'credit_limit' => 'nullable|numeric|min:0',
        ]);
        $customer->update($validated);
        ActivityLog::record('updated', "Updated customer: {$customer->name}", $customer);
        return redirect()->route('admin.customers.index')->with('success', 'Customer updated successfully.');
    }

    public function destroy(Customer $customer)
    {
        ActivityLog::record('deleted', "Deleted customer: {$customer->name}", $customer);
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', 'Customer deleted.');
    }
}
