<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">📋 Expense Report</h2>
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

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 border-l-4 border-orange-400">
                    <p class="text-sm text-gray-500 mb-1">Total Expenses</p>
                    <p class="text-3xl font-bold text-orange-600">${{ number_format($total, 2) }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                    <p class="text-sm font-semibold text-gray-600 dark:text-gray-300 mb-3">By Category</p>
                    @foreach($byCategory as $cat => $amount)
                    <div class="flex justify-between text-sm mb-1">
                        <span class="text-gray-600 dark:text-gray-400">{{ $cat }}</span>
                        <span class="font-semibold text-gray-900 dark:text-white">${{ number_format($amount, 2) }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">By</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @forelse($expenses as $e)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $e->expense_date->format('d M Y') }}</td>
                            <td class="px-6 py-4"><span class="px-2 py-1 text-xs rounded-full bg-orange-100 text-orange-700 font-semibold">{{ $e->category }}</span></td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $e->description }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $e->user->name }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-right text-orange-600">${{ number_format($e->amount, 2) }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="px-6 py-8 text-center text-gray-400">No expenses in this period.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</x-app-layout>
