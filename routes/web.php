<?php

use App\Http\Controllers\ProductController;
use App\Http\Controllers\POSController;
use App\Http\Controllers\Admin;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Manager\DashboardController as ManagerDashboardController;
use App\Http\Controllers\Manager\SupplierController as ManagerSupplierController;
use App\Http\Controllers\Manager\SaleController as ManagerSaleController;
use App\Http\Controllers\Manager\ActivityLogController as ManagerActivityLogController;
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

// Logout Route (explicitly defined)
Route::post('/logout', function () {
    auth()->logout();
    return redirect('/');
})->name('logout');

// Admin-only Routes
Route::prefix('admin')->name('admin.')->middleware(['auth', 'admin'])->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // User Management
    Route::resource('users', Admin\UserController::class);

    // Product Management
    Route::resource('products', ProductController::class);
    Route::post('products/{product}/adjust-stock', [ProductController::class, 'adjustStock'])->name('products.adjust-stock');
    Route::get('products/search/ajax', [ProductController::class, 'search'])->name('products.search');
    Route::get('products/low-stock/ajax', [ProductController::class, 'lowStock'])->name('products.low-stock');

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
    Route::get('/dashboard', [ManagerDashboardController::class, 'index'])->name('dashboard');

    // Product Management - FULL CRUD with stock adjustment
    Route::resource('products', ProductController::class);
    Route::post('products/{product}/adjust-stock', [ProductController::class, 'adjustStock'])->name('products.adjust-stock');
    Route::get('products/search/ajax', [ProductController::class, 'search'])->name('products.search');
    Route::get('products/low-stock/ajax', [ProductController::class, 'lowStock'])->name('products.low-stock');

    // Supplier Management
    Route::resource('suppliers', ManagerSupplierController::class);
    Route::get('suppliers/export/csv', [ManagerSupplierController::class, 'export'])->name('suppliers.export');
    Route::get('suppliers/search/ajax', [ManagerSupplierController::class, 'search'])->name('suppliers.search');

    // Purchase Orders & GRN
    Route::resource('purchase-orders', Admin\PurchaseOrderController::class)->except(['edit', 'update', 'destroy']);
    Route::post('purchase-orders/{purchaseOrder}/receive', [Admin\PurchaseOrderController::class, 'receive'])
        ->name('purchase-orders.receive');

    // Sales Management (with stock deduction)
    Route::get('sales', [ManagerSaleController::class, 'index'])->name('sales.index');
    Route::get('sales/create', [ManagerSaleController::class, 'create'])->name('sales.create');
    Route::post('sales', [ManagerSaleController::class, 'store'])->name('sales.store');
    Route::get('sales/{sale}', [ManagerSaleController::class, 'show'])->name('sales.show');
    Route::post('sales/{sale}/refund', [ManagerSaleController::class, 'refund'])->name('sales.refund');

    // Customer Management
    Route::resource('customers', Admin\CustomerController::class);

    // Expense Management
    Route::resource('expenses', Admin\ExpenseController::class);

    // Reports
    Route::get('reports/sales', [Admin\ReportController::class, 'sales'])->name('reports.sales');
    Route::get('reports/profit', [Admin\ReportController::class, 'profit'])->name('reports.profit');
    Route::get('reports/stock', [Admin\ReportController::class, 'stock'])->name('reports.stock');
    Route::get('reports/expenses', [Admin\ReportController::class, 'expenses'])->name('reports.expenses');

    // Activity Logs - Using Manager Controller
    Route::get('activity-logs', [ManagerActivityLogController::class, 'index'])->name('activity-logs.index');
    Route::get('activity-logs/{activityLog}', [ManagerActivityLogController::class, 'show'])->name('activity-logs.show');
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
    
    // Cashier POS
    Route::get('/pos', [POSController::class, 'index'])->name('pos.index');
    Route::post('/pos/checkout', [POSController::class, 'checkout'])->name('pos.checkout');
});

// Load auth routes (Volt / Livewire login, register, password reset, verify)
require __DIR__ . '/auth.php';