<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of products.
     */
    public function index(Request $request)
    {
        $query = Product::with('category');
        
        // Search filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('barcode', 'like', "%{$search}%");
            });
        }
        
        // Category filter
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        
        // Stock status filter
        if ($request->filled('stock_status')) {
            switch($request->stock_status) {
                case 'low':
                    $query->where('stock_quantity', '<=', 10)->where('stock_quantity', '>', 0);
                    break;
                case 'out':
                    $query->where('stock_quantity', '<=', 0);
                    break;
                case 'in':
                    $query->where('stock_quantity', '>', 10);
                    break;
            }
        }
        
        $products = $query->latest()->paginate(15);
        $categories = Category::all();
        
        return view('manager.products.index', compact('products', 'categories'));
    }

    /**
     * Show the form for creating a new product.
     */
    public function create()
    {
        $categories = Category::all();
        return view('manager.products.create', compact('categories'));
    }

    /**
     * Store a newly created product in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:100|unique:products,barcode',
            'category_id' => 'nullable|exists:categories,id',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();
            
            $product = Product::create($validated);
            
            // Log activity
            ActivityLog::record(
                'created',
                "Created new product: {$product->name} with initial stock: {$product->stock_quantity}",
                $product
            );
            
            DB::commit();
            
            return redirect()
                ->route('manager.products.index')
                ->with('success', 'Product created successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to create product: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified product.
     */
    public function show(Product $product)
    {
        $product->load(['category', 'saleItems.sale', 'purchaseOrderItems.purchaseOrder']);
        
        // Get recent sales for this product
        $recentSales = $product->saleItems()
            ->with('sale.customer')
            ->latest()
            ->limit(10)
            ->get();
        
        // Get stock movement from activity logs
        $stockMovements = ActivityLog::where('model_type', 'Product')
            ->where('model_id', $product->id)
            ->whereIn('action', ['stock_decrease', 'stock_increase'])
            ->latest()
            ->limit(20)
            ->get();
            
        return view('manager.products.show', compact('product', 'recentSales', 'stockMovements'));
    }

    /**
     * Show the form for editing the specified product.
     */
    public function edit(Product $product)
    {
        $categories = Category::all();
        return view('manager.products.edit', compact('product', 'categories'));
    }

    /**
     * Update the specified product in storage.
     */
    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'barcode' => 'nullable|string|max:100|unique:products,barcode,' . $product->id,
            'category_id' => 'nullable|exists:categories,id',
            'cost_price' => 'required|numeric|min:0',
            'selling_price' => 'required|numeric|min:0',
            'stock_quantity' => 'required|integer|min:0',
        ]);

        try {
            DB::beginTransaction();
            
            $oldStock = $product->stock_quantity;
            $product->update($validated);
            
            // Log stock change if different
            if ($oldStock != $product->stock_quantity) {
                $difference = $product->stock_quantity - $oldStock;
                $action = $difference > 0 ? 'stock_increase' : 'stock_decrease';
                ActivityLog::record(
                    $action,
                    "Stock changed from {$oldStock} to {$product->stock_quantity} for product: {$product->name}",
                    $product
                );
            }
            
            ActivityLog::record(
                'updated',
                "Updated product: {$product->name}",
                $product
            );
            
            DB::commit();
            
            return redirect()
                ->route('manager.products.index')
                ->with('success', 'Product updated successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Failed to update product: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified product from storage.
     */
    public function destroy(Product $product)
    {
        try {
            // Check if product has been sold
            if ($product->saleItems()->exists()) {
                return redirect()
                    ->route('manager.products.index')
                    ->with('error', 'Cannot delete product that has been sold. It is kept for historical records.');
            }
            
            DB::beginTransaction();
            
            $productName = $product->name;
            $product->delete();
            
            ActivityLog::record(
                'deleted',
                "Deleted product: {$productName}",
                null
            );
            
            DB::commit();
            
            return redirect()
                ->route('manager.products.index')
                ->with('success', "Product '{$productName}' deleted successfully.");
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->route('manager.products.index')
                ->with('error', 'Failed to delete product: ' . $e->getMessage());
        }
    }
    
    /**
     * Adjust product stock manually
     */
    public function adjustStock(Request $request, Product $product)
    {
        $request->validate([
            'adjustment_type' => 'required|in:add,subtract,set',
            'quantity' => 'required|integer|min:0',
            'reason' => 'nullable|string|max:500',
        ]);
        
        try {
            DB::beginTransaction();
            
            $oldQuantity = $product->stock_quantity;
            
            switch($request->adjustment_type) {
                case 'add':
                    $product->increaseStock($request->quantity);
                    $adjustmentDescription = "Added {$request->quantity} units. Reason: " . ($request->reason ?? 'Manual adjustment');
                    break;
                case 'subtract':
                    if ($request->quantity > $oldQuantity) {
                        throw new \Exception('Cannot subtract more than current stock.');
                    }
                    $product->decreaseStock($request->quantity);
                    $adjustmentDescription = "Removed {$request->quantity} units. Reason: " . ($request->reason ?? 'Manual adjustment');
                    break;
                case 'set':
                    $difference = $request->quantity - $oldQuantity;
                    $product->stock_quantity = $request->quantity;
                    $product->save();
                    $adjustmentDescription = "Set stock from {$oldQuantity} to {$request->quantity}. Difference: {$difference}. Reason: " . ($request->reason ?? 'Manual adjustment');
                    
                    ActivityLog::record(
                        $difference >= 0 ? 'stock_increase' : 'stock_decrease',
                        $adjustmentDescription,
                        $product
                    );
                    break;
            }
            
            DB::commit();
            
            return redirect()
                ->route('manager.products.show', $product)
                ->with('success', "Stock adjusted successfully. {$adjustmentDescription}. New stock: {$product->stock_quantity}");
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            return redirect()
                ->back()
                ->with('error', 'Failed to adjust stock: ' . $e->getMessage());
        }
    }
    
    /**
     * Search products for AJAX (used in sale creation)
     */
    public function search(Request $request)
    {
        $search = $request->get('q');
        
        $products = Product::where('stock_quantity', '>', 0)
            ->where(function($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('barcode', 'like', "%{$search}%");
            })
            ->with('category')
            ->limit(20)
            ->get(['id', 'name', 'barcode', 'selling_price', 'stock_quantity']);
            
        return response()->json($products);
    }
    
    /**
     * Get low stock products for dashboard/widget
     */
    public function lowStock()
    {
        $lowStockProducts = Product::where('stock_quantity', '<=', 10)
            ->where('stock_quantity', '>', 0)
            ->orderBy('stock_quantity', 'asc')
            ->with('category')
            ->get();
            
        return response()->json($lowStockProducts);
    }
}