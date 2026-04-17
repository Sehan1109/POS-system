<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\Sale;
use Illuminate\Http\Request;

class SalesController extends Controller
{
    public function index(Request $request)
    {
        $query = Sale::with(['user', 'customer'])->latest();

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('cashier_id')) {
            $query->where('user_id', $request->cashier_id);
        }
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }

        $sales = $query->paginate(20)->withQueryString();
        return view('admin.sales.index', compact('sales'));
    }

    public function show(Sale $sale)
    {
        $sale->load(['user', 'customer', 'items.product']);
        return view('admin.sales.show', compact('sale'));
    }

    public function approveRefund(Sale $sale)
    {
        if ($sale->status !== 'refund_requested') {
            return back()->with('error', 'This sale does not have a pending refund request.');
        }

        // Restore stock
        foreach ($sale->items as $item) {
            $item->product->increment('stock_quantity', $item->quantity);
        }

        $sale->update(['status' => 'refunded']);
        ActivityLog::record('updated', "Approved refund for Sale #{$sale->id}", $sale);
        return back()->with('success', 'Refund approved and stock restored.');
    }

    public function rejectRefund(Sale $sale)
    {
        if ($sale->status !== 'refund_requested') {
            return back()->with('error', 'This sale does not have a pending refund request.');
        }

        $sale->update(['status' => 'completed']);
        ActivityLog::record('updated', "Rejected refund for Sale #{$sale->id}", $sale);
        return back()->with('success', 'Refund rejected. Sale marked as completed.');
    }
}
