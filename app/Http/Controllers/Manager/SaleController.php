<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Sale;
use App\Models\SaleItem;
use App\Models\Product;
use App\Models\Customer;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SaleController extends Controller
{
    public function index()
    {
        $sales = Sale::with(['user', 'customer'])->latest()->paginate(15);
        return view('manager.sales.index', compact('sales'));
    }

    public function show(Sale $sale)
    {
        $sale->load(['items.product', 'user', 'customer']);
        return view('manager.sales.show', compact('sale'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_id' => 'nullable|exists:customers,id',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string|in:cash,card,bank_transfer',
        ]);

        try {
            DB::beginTransaction();
            
            $subtotal = 0;
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                $subtotal += $product->selling_price * $item['quantity'];
            }
            
            $totalAmount = $subtotal;
            $invoiceNumber = 'INV-' . date('Ymd') . '-' . str_pad(Sale::count() + 1, 4, '0', STR_PAD_LEFT);
            
            $sale = Sale::create([
                'user_id' => auth()->id(),
                'customer_id' => $request->customer_id,
                'invoice_number' => $invoiceNumber,
                'total_amount' => $totalAmount,
                'discount' => 0,
                'tax_amount' => 0,
                'payment_method' => $request->payment_method,
                'status' => 'completed',
            ]);
            
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                
                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }
                
                $product->decreaseStock($item['quantity']);
                
                SaleItem::create([
                    'sale_id' => $sale->id,
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'unit_price' => $product->selling_price,
                    'sub_total' => $product->selling_price * $item['quantity'],
                ]);
            }
            
            // Log activity
            ActivityLog::record(
                'created',
                "New sale created via Manager dashboard. Invoice: {$invoiceNumber}. Total: {$totalAmount}",
                $sale
            );

            DB::commit();
            
            return redirect()->route('manager.sales.show', $sale)->with('success', 'Sale completed successfully.');
                
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', $e->getMessage())->withInput();
        }
    }
}