<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Sale;
use App\Models\SaleItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function index()
    {
        $todaySales = Sale::whereDate('created_at', now()->toDateString())
            ->where('status', '!=', 'refunded')
            ->sum('total_amount');
        
        $lowStockCount = Product::where('stock_quantity', '<=', 10)->count();
        $totalCustomers = \App\Models\Customer::count();
        $monthlyRevenue = Sale::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->where('status', '!=', 'refunded')
            ->sum('total_amount');

        return view('admin.reports.index', compact('todaySales', 'lowStockCount', 'totalCustomers', 'monthlyRevenue'));
    }

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

        // Determine the correct route prefix based on the current user's role
        $routePrefix = auth()->user()->isAdmin() ? 'admin' : 'manager';
        $dailyDetailsUrl  = route($routePrefix . '.reports.sales.daily-details');
        $downloadDailyUrl = route($routePrefix . '.reports.sales.download-daily');

        return view('admin.reports.sales', compact(
            'sales', 'totalRevenue', 'totalCount', 'dateFrom', 'dateTo',
            'dailyDetailsUrl', 'downloadDailyUrl'
        ));
    }

    public function dailyDetails(Request $request)
    {
        $date = $request->get('date', now()->toDateString());

        $sales = Sale::with('customer')
            ->whereDate('created_at', $date)
            ->where('status', '!=', 'refunded')
            ->get()
            ->map(function ($sale) {
                return [
                    'id'             => $sale->id,
                    'invoice_number' => $sale->invoice_number ?? 'N/A',
                    'customer_name'  => optional($sale->customer)->name ?? 'Walk-in',
                    'amount'         => number_format($sale->total_amount, 2),
                ];
            });

        return response()->json($sales);
    }

    public function downloadDaily(Request $request): StreamedResponse
    {
        $date = $request->get('date', now()->toDateString());

        $sales = Sale::with('customer')
            ->whereDate('created_at', $date)
            ->where('status', '!=', 'refunded')
            ->get();

        $filename = 'sales-report-' . $date . '.csv';

        return response()->streamDownload(function () use ($sales) {
            $handle = fopen('php://output', 'w');

            // CSV Header
            fputcsv($handle, ['Invoice #', 'Customer', 'Payment Method', 'Total Amount', 'Time']);

            foreach ($sales as $sale) {
                fputcsv($handle, [
                    $sale->invoice_number ?? 'N/A',
                    optional($sale->customer)->name ?? 'Walk-in',
                    ucfirst($sale->payment_method ?? ''),
                    number_format($sale->total_amount, 2),
                    $sale->created_at->format('H:i:s'),
                ]);
            }

            fclose($handle);
        }, $filename, [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
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

    public function inventory()
    {
        $products = Product::with('category')->get();
        
        $totalCostValue = $products->sum(fn($p) => $p->cost_price * $p->stock_quantity);
        $totalRetailValue = $products->sum(fn($p) => $p->selling_price * $p->stock_quantity);
        $potentialProfit = $totalRetailValue - $totalCostValue;

        return view('admin.reports.inventory', compact('products', 'totalCostValue', 'totalRetailValue', 'potentialProfit'));
    }

    public function customers()
    {
        $customers = \App\Models\Customer::withCount(['sales' => function($q) {
            $q->where('status', '!=', 'refunded');
        }])
        ->withSum(['sales' => function($q) {
            $q->where('status', '!=', 'refunded');
        }], 'total_amount')
        ->orderByDesc('sales_sum_total_amount')
        ->limit(50)
        ->get();

        return view('admin.reports.customers', compact('customers'));
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
