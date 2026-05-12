<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                📦 {{ __('Inventory Value Report') }}
            </h2>
            <button onclick="window.print()" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm shadow">Print Report</button>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <p class="text-sm text-gray-500">Total Cost Value</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($totalCostValue, 2) }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <p class="text-sm text-gray-500">Total Retail Value</p>
                    <p class="text-2xl font-bold text-gray-900 dark:text-white">${{ number_format($totalRetailValue, 2) }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 p-6 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700">
                    <p class="text-sm text-gray-500">Potential Gross Profit</p>
                    <p class="text-2xl font-bold text-emerald-600">${{ number_format($potentialProfit, 2) }}</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Avg. Cost</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Retail Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cost Value</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Retail Value</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($products as $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/50 transition-colors">
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900 dark:text-white">{{ $product->name }}</div>
                                <div class="text-xs text-gray-500">{{ $product->category->name ?? 'No Category' }}</div>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">
                                <span class="{{ $product->stock_quantity <= 10 ? 'text-red-500 font-bold' : '' }}">
                                    {{ $product->stock_quantity }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">${{ number_format($product->cost_price, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">${{ number_format($product->selling_price, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white font-medium">${{ number_format($product->cost_price * $product->stock_quantity, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white font-medium">${{ number_format($product->selling_price * $product->stock_quantity, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
