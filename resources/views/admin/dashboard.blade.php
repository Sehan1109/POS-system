<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-6 mb-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Revenue</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">${{ number_format($data['total_sales'], 2) }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-emerald-500">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Today's Sales</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">${{ number_format($data['today_sales'], 2) }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-indigo-500">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">This Month Profit</p>
                    <p class="text-3xl font-bold {{ $data['month_profit'] >= 0 ? 'text-indigo-600 dark:text-indigo-400' : 'text-red-600 dark:text-red-400' }}">
                        ${{ number_format($data['month_profit'], 2) }}
                    </p>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-amber-500">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Products / Low Stock</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $data['total_products'] }} / {{ $data['low_stock'] }}</p>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-rose-500">
                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Users / Customers</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $data['total_users'] }} / {{ $data['total_customers'] }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                <a href="{{ route('admin.users.index') }}" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">User Management</h3>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">Create and manage staff accounts.</p>
                </a>

                <a href="{{ route('admin.products.index') }}" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Inventory</h3>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">Track items, prices, and stock levels.</p>
                </a>

                <a href="{{ route('admin.reports.sales') }}" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Sales Reports</h3>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">Analyze daily and monthly performance.</p>
                </a>

                <a href="{{ route('admin.activity-logs.index') }}" class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Activity Logs</h3>
                    <p class="text-gray-500 dark:text-gray-400 mt-1">Audit user and system actions quickly.</p>
                </a>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Recent Sales</h3>
                        <a href="{{ route('admin.sales.index') }}" class="text-sm text-blue-600 hover:text-blue-700">View all</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left text-gray-500 dark:text-gray-400 border-b border-gray-200 dark:border-gray-700">
                                    <th class="py-2 pr-3">Invoice</th>
                                    <th class="py-2 pr-3">Cashier</th>
                                    <th class="py-2 pr-3">Status</th>
                                    <th class="py-2 text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentSales as $sale)
                                    <tr class="border-b border-gray-100 dark:border-gray-700/60">
                                        <td class="py-2 pr-3 text-gray-900 dark:text-gray-100">{{ $sale->invoice_number ?? ('SALE-' . $sale->id) }}</td>
                                        <td class="py-2 pr-3 text-gray-700 dark:text-gray-300">{{ $sale->user?->name ?? 'N/A' }}</td>
                                        <td class="py-2 pr-3">
                                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $sale->status === 'completed' ? 'bg-green-100 text-green-700 dark:bg-green-900/40 dark:text-green-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300' }}">
                                                {{ ucfirst(str_replace('_', ' ', $sale->status)) }}
                                            </span>
                                        </td>
                                        <td class="py-2 text-right font-semibold text-gray-900 dark:text-gray-100">${{ number_format($sale->total_amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-4 text-center text-gray-500 dark:text-gray-400">No recent sales found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Low Stock Watchlist</h3>
                        <a href="{{ route('admin.reports.stock') }}" class="text-sm text-blue-600 hover:text-blue-700">Stock report</a>
                    </div>

                    <div class="space-y-3">
                        @forelse ($lowStockProducts as $product)
                            <div class="flex items-center justify-between border border-gray-200 dark:border-gray-700 rounded-lg p-3">
                                <div>
                                    <p class="font-medium text-gray-900 dark:text-gray-100">{{ $product->name }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">{{ $product->category?->name ?? 'Uncategorized' }}</p>
                                </div>
                                <span class="px-2 py-1 rounded-full text-xs font-semibold {{ $product->stock_quantity <= 0 ? 'bg-red-100 text-red-700 dark:bg-red-900/40 dark:text-red-300' : 'bg-amber-100 text-amber-700 dark:bg-amber-900/40 dark:text-amber-300' }}">
                                    {{ $product->stock_quantity }} in stock
                                </span>
                            </div>
                        @empty
                            <p class="text-sm text-gray-500 dark:text-gray-400">All products have healthy stock levels.</p>
                        @endforelse
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-3 text-center">
                        <div class="rounded-lg bg-gray-50 dark:bg-gray-900 p-3">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Out of Stock</p>
                            <p class="text-xl font-bold text-red-600 dark:text-red-400">{{ $data['out_of_stock'] }}</p>
                        </div>
                        <div class="rounded-lg bg-gray-50 dark:bg-gray-900 p-3">
                            <p class="text-xs text-gray-500 dark:text-gray-400">Pending Refunds</p>
                            <p class="text-xl font-bold text-amber-600 dark:text-amber-400">{{ $data['pending_refunds'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>