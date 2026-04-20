<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\Product;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class POSController extends Controller
{
    /**
     * Load the POS terminal view.
     */
    public function index()
    {
        // This will load the Blade view which houses our Livewire POS component
        return view('pos.index');
    }

    /**
     * Handle the checkout process (via AJAX or standard form request).
     * Note: Depending on your Livewire setup in Step 4, this logic might
     * either sit here and be called via fetch/axios, or exist directly in the Livewire component.
     */
    public function checkout(Request $request)
    {
        $request->validate([
            'items'              => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity'   => 'required|integer|min:1',
            'payment_method'     => 'required|string',
            'discount'           => 'nullable|numeric|min:0'
        ]);

        try {
            DB::beginTransaction();

            $totalAmount = 0;
            $discount = $request->discount ?? 0;

            // 1. Calculate grand total securely based on DB prices and check stock
            foreach ($request->items as $item) {
                // We lock the row for update to prevent race conditions in highly concurrent environments
                $product = Product::where('id', $item['product_id'])->lockForUpdate()->first();
                
                if ($product->stock_quantity < $item['quantity']) {
                    throw new \Exception("Insufficient stock for {$product->name}. Only {$product->stock_quantity} left.");
                }
                
                $totalAmount += ($product->selling_price * $item['quantity']);
            }

            $finalAmount = max(0, $totalAmount - $discount);

            // 2. Create the Sale record
            $sale = Sale::create([
                // Defaulting to 1 for testing if no auth user is present yet
                'user_id'        => auth()->id() ?? 1, 
                'total_amount'   => $finalAmount,
                'discount'       => $discount,
                'payment_method' => $request->payment_method,
            ]);

            // 3. Create Sale Items & Deduct Stock
            foreach ($request->items as $item) {
                $product = Product::find($item['product_id']);
                $quantity = $item['quantity'];
                $subTotal = $product->selling_price * $quantity;

                SaleItem::create([
                    'sale_id'    => $sale->id,
                    'product_id' => $product->id,
                    'quantity'   => $quantity,
                    'unit_price' => $product->selling_price,
                    'sub_total'  => $subTotal,
                ]);

                // Reduce the product stock
                $product->decrement('stock_quantity', $quantity);
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Payment successful!',
                'sale_id' => $sale->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        }
    }
}