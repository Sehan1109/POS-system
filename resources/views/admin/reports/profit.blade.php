<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">💰 Profit Report</h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <form method="GET" class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 flex flex-wrap gap-3 items-end">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">From</label>
                    <input type="date" name="date_from" value="{{ $dateFrom }}"
                           class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">To</label>
                    <input type="date" name="date_to" value="{{ $dateTo }}"
                           class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm">
                </div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white text-sm rounded-lg shadow">Apply</button>
            </form>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 border-l-4 border-blue-500">
                    <p class="text-sm text-gray-500 mb-1">Total Revenue</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">${{ number_format($totalRevenue, 2) }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 border-l-4 border-red-400">
                    <p class="text-sm text-gray-500 mb-1">Total Cost</p>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">${{ number_format($totalCost, 2) }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 border-l-4 {{ $totalProfit >= 0 ? 'border-green-500' : 'border-red-600' }}">
                    <p class="text-sm text-gray-500 mb-1">Net Profit</p>
                    <p class="text-3xl font-bold {{ $totalProfit >= 0 ? 'text-green-600' : 'text-red-600' }}">
                        ${{ number_format($totalProfit, 2) }}
                    </p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <h3 class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider border-b border-gray-200 dark:border-gray-700">
                    Item Breakdown
                </h3>
                <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty Sold</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Revenue</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cost</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Profit</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($items->groupBy('product_id') as $productId => $group)
                        @php
                            $first    = $group->first();
                            $qty      = $group->sum('quantity');
                            $rev      = $group->sum(fn($i) => $i->unit_price * $i->quantity);
                            $cost     = $group->sum(fn($i) => $i->product->cost_price * $i->quantity);
                            $profit   = $rev - $cost;
                        @endphp
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $first->product->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $qty }}</td>
                            <td class="px-6 py-4 text-sm text-blue-600 font-semibold">${{ number_format($rev, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-red-500">${{ number_format($cost, 2) }}</td>
                            <td class="px-6 py-4 text-sm font-bold {{ $profit >= 0 ? 'text-green-600' : 'text-red-600' }}">${{ number_format($profit, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">No data in this period.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
