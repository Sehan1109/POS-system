<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\Admin;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login');
});

// Dashboard Route with role-based redirection
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->isAdmin()) {
        return redirect()->route('admin.dashboard');
    } elseif ($user->isManager()) {
        return redirect()->route('manager.dashboard');
    } elseif ($user->isCashier()) {
        return redirect()->route('cashier.dashboard');
    }

    return redirect()->route('pos.index');
})->middleware('auth')->name('dashboard');

// Auth Required Routes
Route::middleware('auth')->group(function () {
    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
    Route::post('/pos/checkout', [POSController::class, 'checkout'])->name('pos.checkout');
    Route::view('profile', 'profile')->name('profile');
});

// Admin-only Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    // Admin Dashboard
    Route::get('/dashboard', function () {
        $data = [
            'total_sales' => \App\Models\Sale::sum('total_amount'),
            'total_products' => \App\Models\Product::count(),
            'low_stock' => \App\Models\Product::where('stock_quantity', '<=', 10)->count(),
            'total_users' => \App\Models\User::count(),
        ];
        return view('admin.dashboard', compact('data'));
    })->name('dashboard');

    // User Management
    Route::resource('users', Admin\UserController::class);

    // Product Management
    Route::resource('products', ProductController::class);

    // Supplier Management
    Route::resource('suppliers', Admin\SupplierController::class);

    // Purchase Orders & GRN
    Route::resource('purchase-orders', Admin\PurchaseOrderController::class)->except(['edit', 'update', 'destroy']);
    Route::post('purchase-orders/{purchaseOrder}/receive', [Admin\PurchaseOrderController::class, 'receive'])
        ->name('purchase-orders.receive');

    // Sales Monitoring & Refunds
    Route::get('sales', [Admin\SalesController::class, 'index'])->name('sales.index');
    Route::get('sales/{sale}', [Admin\SalesController::class, 'show'])->name('sales.show');
    Route::post('sales/{sale}/refund/approve', [Admin\SalesController::class, 'approveRefund'])->name('sales.refund.approve');
    Route::post('sales/{sale}/refund/reject', [Admin\SalesController::class, 'rejectRefund'])->name('sales.refund.reject');

    // Customer Management
    Route::resource('customers', Admin\CustomerController::class);

    // Expense Management
    Route::resource('expenses', Admin\ExpenseController::class);

    // Reports
    Route::get('reports/sales', [Admin\ReportController::class, 'sales'])->name('reports.sales');
    Route::get('reports/profit', [Admin\ReportController::class, 'profit'])->name('reports.profit');
    Route::get('reports/stock', [Admin\ReportController::class, 'stock'])->name('reports.stock');
    Route::get('reports/expenses', [Admin\ReportController::class, 'expenses'])->name('reports.expenses');

    // System Settings
    Route::get('settings', [Admin\SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [Admin\SettingsController::class, 'update'])->name('settings.update');

    // Activity Logs
    Route::get('activity-logs', [Admin\ActivityLogController::class, 'index'])->name('activity-logs.index');
});

// Manager Routes
Route::prefix('manager')->name('manager.')->middleware(['auth', 'manager'])->group(function () {
    // Manager Dashboard
    Route::get('/dashboard', function () {
        $data = [
            'total_sales' => \App\Models\Sale::sum('total_amount'),
            'total_products' => \App\Models\Product::count(),
            'low_stock' => \App\Models\Product::where('stock_quantity', '<=', 10)->count(),
        ];
        return view('manager.dashboard', compact('data'));
    })->name('dashboard');

    // Product Management
    Route::resource('products', ProductController::class);

    // Supplier Management
    Route::resource('suppliers', Admin\SupplierController::class);

    // Purchase Orders & GRN
    Route::resource('purchase-orders', Admin\PurchaseOrderController::class)->except(['edit', 'update', 'destroy']);
    Route::post('purchase-orders/{purchaseOrder}/receive', [Admin\PurchaseOrderController::class, 'receive'])
        ->name('purchase-orders.receive');

    // Sales Monitoring (view only)
    Route::get('sales', [Admin\SalesController::class, 'index'])->name('sales.index');
    Route::get('sales/{sale}', [Admin\SalesController::class, 'show'])->name('sales.show');

    // Customer Management
    Route::resource('customers', Admin\CustomerController::class);

    // Expense Management
    Route::resource('expenses', Admin\ExpenseController::class);

    // Reports
    Route::get('reports/sales', [Admin\ReportController::class, 'sales'])->name('reports.sales');
    Route::get('reports/profit', [Admin\ReportController::class, 'profit'])->name('reports.profit');
    Route::get('reports/stock', [Admin\ReportController::class, 'stock'])->name('reports.stock');
    Route::get('reports/expenses', [Admin\ReportController::class, 'expenses'])->name('reports.expenses');

    // Activity Logs
    Route::get('activity-logs', [Admin\ActivityLogController::class, 'index'])->name('activity-logs.index');
});

// Cashier Routes
Route::prefix('cashier')->name('cashier.')->middleware(['auth', 'cashier'])->group(function () {
    Route::get('/dashboard', function () {
        $data = [
            'total_sales' => \App\Models\Sale::sum('total_amount'),
            'total_products' => \App\Models\Product::count(),
            'low_stock' => \App\Models\Product::where('stock_quantity', '<=', 10)->count(),
        ];
        return view('cashier.dashboard', compact('data'));
    })->name('dashboard');
});

// Load auth routes (Volt / Livewire login, register, password reset, verify)
require __DIR__ . '/auth.php';
