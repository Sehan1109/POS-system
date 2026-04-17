<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Expense;
use App\Models\Product;
use App\Models\Sale;

class DashboardController extends Controller
{
    public function index()
    {
        $monthStart = now()->startOfMonth();
        $monthEnd = now()->endOfMonth();

        $data = [
            'today_sales' => (float) Sale::whereDate('created_at', today())->where('status', '!=', 'refunded')->sum('total_amount'),
            'month_sales' => (float) Sale::whereBetween('created_at', [$monthStart, $monthEnd])->where('status', '!=', 'refunded')->sum('total_amount'),
            'month_expenses' => (float) Expense::whereBetween('expense_date', [$monthStart->toDateString(), $monthEnd->toDateString()])->sum('amount'),
            'total_products' => Product::count(),
            'low_stock' => Product::where('stock_quantity', '<=', 10)->where('stock_quantity', '>', 0)->count(),
            'out_of_stock' => Product::where('stock_quantity', '<=', 0)->count(),
            'total_customers' => Customer::count(),
            'pending_refunds' => Sale::where('status', 'refund_requested')->count(),
        ];

        $data['month_profit'] = $data['month_sales'] - $data['month_expenses'];

        $recentSales = Sale::with(['user', 'customer'])
            ->latest()
            ->take(6)
            ->get();

        $lowStockProducts = Product::with('category')
            ->where('stock_quantity', '<=', 10)
            ->orderBy('stock_quantity')
            ->take(6)
            ->get();

        return view('manager.dashboard', compact('data', 'recentSales', 'lowStockProducts'));
    }
}
