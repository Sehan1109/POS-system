<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function sales(Request $request)
    {
        $period = $request->get('period', 'monthly');
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo   = $request->get('date_to', now()->toDateString());

        $sales = Sale::whereBetween(DB::raw('DATE(created_at)'), [$dateFrom, $dateTo])
            ->where('status', '!=', 'refunded')
            ->selectRaw('DATE(created_at) as date, COUNT(*) as count, SUM(total_amount) as revenue')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $totalRevenue = $sales->sum('revenue');
        $totalCount   = $sales->sum('count');

        return view('admin.reports.sales', compact('sales', 'totalRevenue', 'totalCount', 'dateFrom', 'dateTo'));
    }

    public function profit(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo   = $request->get('date_to', now()->toDateString());

        $items = SaleItem::with(['product', 'sale'])
            ->whereHas('sale', function ($q) use ($dateFrom, $dateTo) {
                $q->whereBetween(DB::raw('DATE(created_at)'), [$dateFrom, $dateTo])
                  ->where('status', '!=', 'refunded');
            })
            ->get();

        $totalRevenue = $items->sum(fn($i) => $i->unit_price * $i->quantity);
        $totalCost    = $items->sum(fn($i) => $i->product->cost_price * $i->quantity);
        $totalProfit  = $totalRevenue - $totalCost;

        return view('admin.reports.profit', compact('items', 'totalRevenue', 'totalCost', 'totalProfit', 'dateFrom', 'dateTo'));
    }

    public function stock()
    {
        $products    = Product::with('category')->orderBy('stock_quantity')->paginate(20);
        $lowStock    = Product::where('stock_quantity', '<=', 10)->count();
        $outOfStock  = Product::where('stock_quantity', 0)->count();

        return view('admin.reports.stock', compact('products', 'lowStock', 'outOfStock'));
    }

    public function expenses(Request $request)
    {
        $dateFrom = $request->get('date_from', now()->startOfMonth()->toDateString());
        $dateTo   = $request->get('date_to', now()->toDateString());

        $expenses = Expense::with('user')
            ->whereBetween('expense_date', [$dateFrom, $dateTo])
            ->latest('expense_date')
            ->get();

        $byCategory = $expenses->groupBy('category')->map->sum('amount');
        $total      = $expenses->sum('amount');

        return view('admin.reports.expenses', compact('expenses', 'byCategory', 'total', 'dateFrom', 'dateTo'));
    }
}
