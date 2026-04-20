<x-app-layout>
    <x-slot name="header">
        <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
            <div>
                <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Operations Desk</p>
                <h2 class="text-2xl font-bold text-slate-900 leading-tight">
                    {{ __('Manager Dashboard') }}
                </h2>
            </div>
            <p class="text-sm text-slate-600">{{ now()->format('l, d M Y') }}</p>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <section class="mb-6 rounded-2xl bg-gradient-to-r from-teal-800 via-cyan-800 to-slate-900 px-6 py-7 text-white shadow-lg">
                <div class="grid gap-6 lg:grid-cols-[2fr,1fr] lg:items-center">
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-teal-100">Daily Brief</p>
                        <h3 class="mt-2 text-2xl font-semibold">Keep operations smooth and stock under control.</h3>
                        <p class="mt-2 text-sm text-cyan-100 max-w-2xl">Track sales pace, monitor inventory pressure, and jump directly to reports or purchasing actions.</p>
                    </div>
                    <div class="grid grid-cols-2 gap-3 text-center">
                        <div class="rounded-xl bg-white/10 p-3 backdrop-blur-sm">
                            <p class="text-[11px] uppercase tracking-wide text-teal-100">Pending Refunds</p>
                            <p class="mt-1 text-lg font-semibold">{{ $data['pending_refunds'] }}</p>
                        </div>
                        <div class="rounded-xl bg-white/10 p-3 backdrop-blur-sm">
                            <p class="text-[11px] uppercase tracking-wide text-teal-100">Low Stock</p>
                            <p class="mt-1 text-lg font-semibold">{{ $data['low_stock'] }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-5 mb-7">
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Today's Sales</p>
                    <p class="text-3xl font-bold text-slate-900">${{ number_format($data['today_sales'], 2) }}</p>
                    <p class="mt-2 text-xs text-slate-500">Current-day revenue</p>
                </div>

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Monthly Sales</p>
                    <p class="text-3xl font-bold text-emerald-600">${{ number_format($data['month_sales'], 2) }}</p>
                    <p class="mt-2 text-xs text-slate-500">Month-to-date total</p>
                </div>

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Monthly Profit</p>
                    <p class="text-3xl font-bold {{ $data['month_profit'] >= 0 ? 'text-cyan-700' : 'text-rose-600' }}">
                        ${{ number_format($data['month_profit'], 2) }}
                    </p>
                    <p class="mt-2 text-xs text-slate-500">After operating expenses</p>
                </div>

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Products / Low Stock</p>
                    <p class="text-3xl font-bold text-slate-900">{{ $data['total_products'] }} / {{ $data['low_stock'] }}</p>
                    <p class="mt-2 text-xs text-slate-500">Inventory pressure index</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-7">
                <a href="{{ route('manager.products.index') }}" class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-0.5 hover:shadow-md">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Operations Action</p>
                    <h3 class="mt-2 text-lg font-semibold text-slate-900">Inventory</h3>
                    <p class="mt-1 text-sm text-slate-600">Update products and stock levels.</p>
                    <span class="mt-3 inline-block text-sm font-medium text-cyan-700 group-hover:text-cyan-800">Open module -></span>
                </a>

                <a href="{{ route('manager.suppliers.index') }}" class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-0.5 hover:shadow-md">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Operations Action</p>
                    <h3 class="mt-2 text-lg font-semibold text-slate-900">Suppliers</h3>
                    <p class="mt-1 text-sm text-slate-600">Manage supplier relationships.</p>
                    <span class="mt-3 inline-block text-sm font-medium text-cyan-700 group-hover:text-cyan-800">Open module -></span>
                </a>

                <a href="{{ route('manager.reports.sales') }}" class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-0.5 hover:shadow-md">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Operations Action</p>
                    <h3 class="mt-2 text-lg font-semibold text-slate-900">Sales Reports</h3>
                    <p class="mt-1 text-sm text-slate-600">Review store performance trends.</p>
                    <span class="mt-3 inline-block text-sm font-medium text-cyan-700 group-hover:text-cyan-800">Open module -></span>
                </a>

                <a href="{{ route('manager.activity-logs.index') }}" class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-0.5 hover:shadow-md">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Operations Action</p>
                    <h3 class="mt-2 text-lg font-semibold text-slate-900">Activity Logs</h3>
                    <p class="mt-1 text-sm text-slate-600">Monitor day-to-day operations.</p>
                    <span class="mt-3 inline-block text-sm font-medium text-cyan-700 group-hover:text-cyan-800">Open module -></span>
                </a>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-slate-900">Recent Sales</h3>
                        <a href="{{ route('manager.sales.index') }}" class="text-sm font-medium text-cyan-700 hover:text-cyan-800">View all</a>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="min-w-full text-sm">
                            <thead>
                                <tr class="text-left text-slate-500 border-b border-slate-200">
                                    <th class="py-2 pr-3">Invoice</th>
                                    <th class="py-2 pr-3">Cashier</th>
                                    <th class="py-2 pr-3">Status</th>
                                    <th class="py-2 text-right">Amount</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($recentSales as $sale)
                                    <tr class="border-b border-slate-100">
                                        <td class="py-2 pr-3 font-medium text-slate-900">{{ $sale->invoice_number ?? ('SALE-' . $sale->id) }}</td>
                                        <td class="py-2 pr-3 text-slate-700">{{ $sale->user?->name ?? 'N/A' }}</td>
                                        <td class="py-2 pr-3">
                                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $sale->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                                {{ ucfirst(str_replace('_', ' ', $sale->status)) }}
                                            </span>
                                        </td>
                                        <td class="py-2 text-right font-semibold text-slate-900">${{ number_format($sale->total_amount, 2) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="py-4 text-center text-slate-500">No recent sales found.</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <h3 class="text-lg font-semibold text-slate-900 mb-4">Operations Snapshot</h3>

                    <div class="grid grid-cols-2 gap-3 mb-5 text-center">
                        <div class="rounded-xl bg-slate-50 p-3">
                            <p class="text-xs text-slate-500">Out of Stock</p>
                            <p class="text-xl font-bold text-rose-600">{{ $data['out_of_stock'] }}</p>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-3">
                            <p class="text-xs text-slate-500">Pending Refunds</p>
                            <p class="text-xl font-bold text-amber-600">{{ $data['pending_refunds'] }}</p>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-3">
                            <p class="text-xs text-slate-500">Customers</p>
                            <p class="text-xl font-bold text-cyan-700">{{ $data['total_customers'] }}</p>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-3">
                            <p class="text-xs text-slate-500">Expenses (Month)</p>
                            <p class="text-xl font-bold text-slate-900">${{ number_format($data['month_expenses'], 2) }}</p>
                        </div>
                    </div>

                    <h4 class="text-sm font-semibold text-slate-700 mb-2">Low Stock Watchlist</h4>
                    <div class="space-y-2">
                        @forelse ($lowStockProducts as $product)
                            <div class="flex items-center justify-between rounded-xl border border-slate-200 p-3">
                                <span class="text-sm text-slate-900">{{ $product->name }}</span>
                                <span class="text-xs font-semibold {{ $product->stock_quantity <= 0 ? 'text-rose-600' : 'text-amber-600' }}">
                                    {{ $product->stock_quantity }} in stock
                                </span>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No low stock products at the moment.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>