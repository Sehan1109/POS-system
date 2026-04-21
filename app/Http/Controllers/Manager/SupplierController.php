<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{
    /**
     * Display a listing of suppliers.
     */
    public function index()
    {
        $suppliers = Supplier::latest()->paginate(10);
        return view('manager.suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new supplier.
     */
    public function create()
    {
        return view('manager.suppliers.create');
    }

    /**
     * Store a newly created supplier in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255|unique:suppliers,email',
            'address' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            
            $supplier = Supplier::create($validated);
            
            DB::commit();
            
            return redirect()
                ->route('manager.suppliers.index')
                ->with('success', 'Supplier created successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create supplier: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified supplier.
     */
    public function show(Supplier $supplier)
    {
        $supplier->load(['purchaseOrders' => function($query) {
            $query->latest()->limit(10);
        }]);
        
        return view('manager.suppliers.show', compact('supplier'));
    }

    /**
     * Show the form for editing the specified supplier.
     */
    public function edit(Supplier $supplier)
    {
        return view('manager.suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified supplier in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'required|string|max:20',
            'email' => 'nullable|email|max:255|unique:suppliers,email,' . $supplier->id,
            'address' => 'nullable|string',
        ]);

        try {
            DB::beginTransaction();
            
            $supplier->update($validated);
            
            DB::commit();
            
            return redirect()
                ->route('manager.suppliers.index')
                ->with('success', 'Supplier updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update supplier: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified supplier from storage.
     */
    public function destroy(Supplier $supplier)
    {
        try {
            // Check if supplier has any purchase orders
            if ($supplier->purchaseOrders()->exists()) {
                return redirect()
                    ->route('manager.suppliers.index')
                    ->with('error', 'Cannot delete supplier with existing purchase orders.');
            }
            
            DB::beginTransaction();
            
            $supplierName = $supplier->name;
            $supplier->delete();
            
            DB::commit();
            
            return redirect()
                ->route('manager.suppliers.index')
                ->with('success', "Supplier '{$supplierName}' deleted successfully.");
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->route('manager.suppliers.index')
                ->with('error', 'Failed to delete supplier: ' . $e->getMessage());
        }
    }
    
    /**
     * Search suppliers via AJAX.
     */
    public function search(Request $request)
    {
        $search = $request->get('q');
        
        $suppliers = Supplier::where('name', 'like', "%{$search}%")
            ->orWhere('phone', 'like', "%{$search}%")
            ->orWhere('email', 'like', "%{$search}%")
            ->limit(10)
            ->get(['id', 'name', 'phone', 'email']);
            
        return response()->json($suppliers);
    }
}