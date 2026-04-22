<x-app-layout>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <section
                class="mb-6 rounded-2xl bg-gradient-to-r from-slate-900 via-slate-800 to-cyan-900 px-6 py-7 text-white shadow-lg">
                <div class="grid gap-6 lg:grid-cols-[2fr,1fr] lg:items-center">
                    <div>
                        <p class="text-xs uppercase tracking-[0.2em] text-cyan-200">Executive Snapshot</p>
                        <h3 class="mt-2 text-2xl font-semibold">Revenue and operations are in one place.</h3>
                        <p class="mt-2 text-sm text-slate-200 max-w-2xl">Use this dashboard to monitor sales health,
                            inventory risk, and pending actions before they become bottlenecks.</p>
                    </div>
                    <div class="grid grid-cols-2 gap-3 text-center">
                        <div class="rounded-xl bg-white/10 p-3 backdrop-blur-sm">
                            <p class="text-[11px] uppercase tracking-wide text-cyan-100">Month Sales</p>
                            <p class="mt-1 text-lg font-semibold">${{ number_format($data['month_sales'], 2) }}</p>
                        </div>
                        <div class="rounded-xl bg-white/10 p-3 backdrop-blur-sm">
                            <p class="text-[11px] uppercase tracking-wide text-cyan-100">Month Profit</p>
                            <p class="mt-1 text-lg font-semibold">${{ number_format($data['month_profit'], 2) }}</p>
                        </div>
                    </div>
                </div>
            </section>

            <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-5 gap-5 mb-7">
                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Total Revenue</p>
                    <p class="text-3xl font-bold text-slate-900">${{ number_format($data['total_sales'], 2) }}</p>
                    <p class="mt-2 text-xs text-slate-500">All-time completed sales</p>
                </div>

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Today's Sales</p>
                    <p class="text-3xl font-bold text-emerald-600">${{ number_format($data['today_sales'], 2) }}</p>
                    <p class="mt-2 text-xs text-slate-500">Transactions from today</p>
                </div>

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">This Month Profit</p>
                    <p class="text-3xl font-bold {{ $data['month_profit'] >= 0 ? 'text-cyan-700' : 'text-rose-600' }}">
                        ${{ number_format($data['month_profit'], 2) }}
                    </p>
                    <p class="mt-2 text-xs text-slate-500">Sales minus expenses</p>
                </div>

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Products / Low Stock
                    </p>
                    <p class="text-3xl font-bold text-slate-900">{{ $data['total_products'] }} /
                        {{ $data['low_stock'] }}</p>
                    <p class="mt-2 text-xs text-slate-500">Catalog size and warnings</p>
                </div>

                <div class="rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500 mb-2">Users / Customers</p>
                    <p class="text-3xl font-bold text-slate-900">{{ $data['total_users'] }} /
                        {{ $data['total_customers'] }}</p>
                    <p class="mt-2 text-xs text-slate-500">Workforce and customer base</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-5 mb-7">
                <a href="{{ route('admin.users.index') }}"
                    class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-0.5 hover:shadow-md">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Admin Action</p>
                    <h3 class="mt-2 text-lg font-semibold text-slate-900">User Management</h3>
                    <p class="mt-1 text-sm text-slate-600">Create and manage staff accounts.</p>
                    <span class="mt-3 inline-block text-sm font-medium text-cyan-700 group-hover:text-cyan-800">Open
                        module -></span>
                </a>

                <a href="{{ route('admin.products.index') }}"
                    class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-0.5 hover:shadow-md">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Admin Action</p>
                    <h3 class="mt-2 text-lg font-semibold text-slate-900">Inventory</h3>
                    <p class="mt-1 text-sm text-slate-600">Track items, prices, and stock levels.</p>
                    <span class="mt-3 inline-block text-sm font-medium text-cyan-700 group-hover:text-cyan-800">Open
                        module -></span>
                </a>

                <a href="{{ route('admin.reports.sales') }}"
                    class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-0.5 hover:shadow-md">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Admin Action</p>
                    <h3 class="mt-2 text-lg font-semibold text-slate-900">Sales Reports</h3>
                    <p class="mt-1 text-sm text-slate-600">Analyze daily and monthly performance.</p>
                    <span class="mt-3 inline-block text-sm font-medium text-cyan-700 group-hover:text-cyan-800">Open
                        module -></span>
                </a>

                <a href="{{ route('admin.activity-logs.index') }}"
                    class="group rounded-2xl bg-white p-5 shadow-sm ring-1 ring-slate-200 transition hover:-translate-y-0.5 hover:shadow-md">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Admin Action</p>
                    <h3 class="mt-2 text-lg font-semibold text-slate-900">Activity Logs</h3>
                    <p class="mt-1 text-sm text-slate-600">Audit user and system actions quickly.</p>
                    <span class="mt-3 inline-block text-sm font-medium text-cyan-700 group-hover:text-cyan-800">Open
                        module -></span>
                </a>
            </div>

            <div class="grid grid-cols-1 xl:grid-cols-2 gap-6">
                <div class="rounded-2xl bg-white p-6 shadow-sm ring-1 ring-slate-200">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-slate-900">Recent Sales</h3>
                        <a href="{{ route('admin.sales.index') }}"
                            class="text-sm font-medium text-cyan-700 hover:text-cyan-800">View all</a>
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
                                        <td class="py-2 pr-3 font-medium text-slate-900">
                                            {{ $sale->invoice_number ?? ('SALE-' . $sale->id) }}</td>
                                        <td class="py-2 pr-3 text-slate-700">{{ $sale->user?->name ?? 'N/A' }}</td>
                                        <td class="py-2 pr-3">
                                            <span
                                                class="px-2 py-1 rounded-full text-xs font-medium {{ $sale->status === 'completed' ? 'bg-emerald-100 text-emerald-700' : 'bg-amber-100 text-amber-700' }}">
                                                {{ ucfirst(str_replace('_', ' ', $sale->status)) }}
                                            </span>
                                        </td>
                                        <td class="py-2 text-right font-semibold text-slate-900">
                                            ${{ number_format($sale->total_amount, 2) }}</td>
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
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-slate-900">Low Stock Watchlist</h3>
                        <a href="{{ route('admin.reports.stock') }}"
                            class="text-sm font-medium text-cyan-700 hover:text-cyan-800">Stock report</a>
                    </div>

                    <div class="space-y-3">
                        @forelse ($lowStockProducts as $product)
                            <div class="flex items-center justify-between rounded-xl border border-slate-200 p-3">
                                <div>
                                    <p class="font-medium text-slate-900">{{ $product->name }}</p>
                                    <p class="text-xs text-slate-500">{{ $product->category?->name ?? 'Uncategorized' }}</p>
                                </div>
                                <span
                                    class="px-2 py-1 rounded-full text-xs font-semibold {{ $product->stock_quantity <= 0 ? 'bg-rose-100 text-rose-700' : 'bg-amber-100 text-amber-700' }}">
                                    {{ $product->stock_quantity }} in stock
                                </span>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">All products have healthy stock levels.</p>
                        @endforelse
                    </div>

                    <div class="mt-5 grid grid-cols-2 gap-3 text-center">
                        <div class="rounded-xl bg-slate-50 p-3">
                            <p class="text-xs text-slate-500">Out of Stock</p>
                            <p class="text-xl font-bold text-rose-600">{{ $data['out_of_stock'] }}</p>
                        </div>
                        <div class="rounded-xl bg-slate-50 p-3">
                            <p class="text-xs text-slate-500">Pending Refunds</p>
                            <p class="text-xl font-bold text-amber-600">{{ $data['pending_refunds'] }}</p>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>