<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\GoodsReceivedNote;
use App\Models\GrnItem;
use App\Models\Product;
use App\Models\PurchaseOrder;
use App\Models\PurchaseOrderItem;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $orders = PurchaseOrder::with(['supplier', 'user'])->latest()->paginate(15);
        return view('admin.purchase-orders.index', compact('orders'));
    }

    public function create()
    {
        $suppliers = Supplier::orderBy('name')->get();
        $products  = Product::orderBy('name')->get();
        return view('admin.purchase-orders.create', compact('suppliers', 'products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'supplier_id'           => 'required|exists:suppliers,id',
            'notes'                 => 'nullable|string',
            'items'                 => 'required|array|min:1',
            'items.*.product_id'    => 'required|exists:products,id',
            'items.*.quantity'      => 'required|integer|min:1',
            'items.*.unit_cost'     => 'required|numeric|min:0',
        ]);

        DB::transaction(function () use ($request) {
            $total = collect($request->items)->sum(fn($i) => $i['quantity'] * $i['unit_cost']);

            $po = PurchaseOrder::create([
                'supplier_id'  => $request->supplier_id,
                'user_id'      => auth()->id(),
                'status'       => 'pending',
                'total_amount' => $total,
                'notes'        => $request->notes,
            ]);

            foreach ($request->items as $item) {
                PurchaseOrderItem::create([
                    'purchase_order_id' => $po->id,
                    'product_id'        => $item['product_id'],
                    'quantity'          => $item['quantity'],
                    'unit_cost'         => $item['unit_cost'],
                ]);
            }

            ActivityLog::record('created', "Created Purchase Order #{$po->id}", $po);
        });

        return redirect()->route('admin.purchase-orders.index')->with('success', 'Purchase order created.');
    }

    public function show(PurchaseOrder $purchaseOrder)
    {
        $purchaseOrder->load(['supplier', 'user', 'items.product', 'goodsReceivedNotes.items.product']);
        return view('admin.purchase-orders.show', compact('purchaseOrder'));
    }

    public function receive(Request $request, PurchaseOrder $purchaseOrder)
    {
        if ($purchaseOrder->status !== 'pending') {
            return back()->with('error', 'Only pending orders can be received.');
        }

        DB::transaction(function () use ($purchaseOrder) {
            $grn = GoodsReceivedNote::create([
                'purchase_order_id' => $purchaseOrder->id,
                'user_id'           => auth()->id(),
                'received_at'       => now(),
                'notes'             => 'Received via admin panel',
            ]);

            foreach ($purchaseOrder->items as $item) {
                GrnItem::create([
                    'goods_received_note_id' => $grn->id,
                    'product_id'             => $item->product_id,
                    'quantity_received'      => $item->quantity,
                ]);
                // Increment stock
                Product::where('id', $item->product_id)->increment('stock_quantity', $item->quantity);
            }

            $purchaseOrder->update(['status' => 'received']);
            ActivityLog::record('updated', "Received Purchase Order #{$purchaseOrder->id} — stock updated", $purchaseOrder);
        });

        return redirect()->route('admin.purchase-orders.show', $purchaseOrder)->with('success', 'Order received and stock updated.');
    }
}
