<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            👥 {{ __('Customer Analysis') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-3xl shadow-sm border border-gray-100 dark:border-gray-700 overflow-hidden">
                <div class="px-8 py-6 border-b border-gray-100 dark:border-gray-700 bg-gray-50/50 dark:bg-gray-800/50">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-white">Top 50 Customers by Spending</h3>
                    <p class="text-sm text-gray-500">Based on total transaction volume across all time.</p>
                </div>
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-800">
                        <tr>
                            <th class="px-8 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Rank</th>
                            <th class="px-8 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Customer</th>
                            <th class="px-8 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Orders</th>
                            <th class="px-8 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total Spent</th>
                            <th class="px-8 py-4 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Avg. Order Value</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach($customers as $index => $customer)
                        <tr class="hover:bg-indigo-50/30 dark:hover:bg-indigo-900/10 transition-colors">
                            <td class="px-8 py-5">
                                <span class="flex items-center justify-center w-8 h-8 rounded-full {{ $index < 3 ? 'bg-amber-100 text-amber-700 font-bold' : 'bg-gray-100 text-gray-600' }}">
                                    {{ $index + 1 }}
                                </span>
                            </td>
                            <td class="px-8 py-5">
                                <div class="text-sm font-bold text-gray-900 dark:text-white">{{ $customer->name }}</div>
                                <div class="text-xs text-gray-500">{{ $customer->phone }}</div>
                            </td>
                            <td class="px-8 py-5 text-sm text-gray-600 dark:text-gray-400">
                                {{ $customer->sales_count }}
                            </td>
                            <td class="px-8 py-5">
                                <div class="text-sm font-bold text-indigo-600 dark:text-indigo-400">${{ number_format($customer->sales_sum_total_amount, 2) }}</div>
                            </td>
                            <td class="px-8 py-5 text-sm text-gray-600 dark:text-gray-400">
                                ${{ number_format($customer->sales_count > 0 ? $customer->sales_sum_total_amount / $customer->sales_count : 0, 2) }}
                            </td>
                        </tr>
                        @endforeach
                        @if($customers->isEmpty())
                        <tr>
                            <td colspan="5" class="px-8 py-10 text-center text-gray-400 italic">No customer data available yet.</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
