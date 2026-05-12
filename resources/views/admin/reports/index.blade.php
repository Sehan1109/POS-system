<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            📊 {{ __('Reports Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Today's Sales</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($todaySales, 2) }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Monthly Revenue</p>
                    <p class="text-2xl font-bold text-indigo-600 dark:text-indigo-400">${{ number_format($monthlyRevenue, 2) }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Total Customers</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalCustomers }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <p class="text-sm text-gray-500 dark:text-gray-400">Low Stock Items</p>
                    <p class="text-2xl font-bold text-red-500">{{ $lowStockCount }}</p>
                </div>
            </div>

            <h3 class="text-lg font-semibold text-gray-700 dark:text-gray-300 mb-6">Available Reports</h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Sales Report Card -->
                <a href="{{ route(auth()->user()->isAdmin() ? 'admin.reports.sales' : 'manager.reports.sales') }}" 
                   class="group bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:border-indigo-500 transition-all duration-300">
                    <div class="w-14 h-14 bg-indigo-100 dark:bg-indigo-900/30 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Sales Analytics</h4>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">Detailed breakdown of daily and monthly revenue, transaction counts, and trends.</p>
                </a>

                <!-- Profit Report Card -->
                <a href="{{ route(auth()->user()->isAdmin() ? 'admin.reports.profit' : 'manager.reports.profit') }}" 
                   class="group bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:border-emerald-500 transition-all duration-300">
                    <div class="w-14 h-14 bg-emerald-100 dark:bg-emerald-900/30 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-emerald-600 dark:text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Profit & Loss</h4>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">Analyze your gross profit by comparing cost prices with actual selling prices.</p>
                </a>

                <!-- Stock Report Card -->
                <a href="{{ route(auth()->user()->isAdmin() ? 'admin.reports.stock' : 'manager.reports.stock') }}" 
                   class="group bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:border-orange-500 transition-all duration-300">
                    <div class="w-14 h-14 bg-orange-100 dark:bg-orange-900/30 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-orange-600 dark:text-orange-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Inventory Levels</h4>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">Monitor stock quantities, identify low-stock items, and manage replenishment.</p>
                </a>

                <!-- Inventory Value Card -->
                <a href="{{ route(auth()->user()->isAdmin() ? 'admin.reports.inventory' : 'manager.reports.inventory') }}" 
                   class="group bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:border-blue-500 transition-all duration-300">
                    <div class="w-14 h-14 bg-blue-100 dark:bg-blue-900/30 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-blue-600 dark:text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Inventory Value</h4>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">Total asset value report based on current stock, cost prices, and retail prices.</p>
                </a>

                <!-- Customer Analysis Card -->
                <a href="{{ route(auth()->user()->isAdmin() ? 'admin.reports.customers' : 'manager.reports.customers') }}" 
                   class="group bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:border-purple-500 transition-all duration-300">
                    <div class="w-14 h-14 bg-purple-100 dark:bg-purple-900/30 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Customer Analysis</h4>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">Identify your most valuable customers based on total spending and order frequency.</p>
                </a>

                <!-- Expense Report Card -->
                <a href="{{ route(auth()->user()->isAdmin() ? 'admin.reports.expenses' : 'manager.reports.expenses') }}" 
                   class="group bg-white dark:bg-gray-800 p-8 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 hover:shadow-xl hover:border-red-500 transition-all duration-300">
                    <div class="w-14 h-14 bg-red-100 dark:bg-red-900/30 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-8 h-8 text-red-600 dark:text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 dark:text-white mb-2">Expense Tracking</h4>
                    <p class="text-gray-500 dark:text-gray-400 text-sm leading-relaxed">Detailed view of operational expenses categorized by type and date range.</p>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
