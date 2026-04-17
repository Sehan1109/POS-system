<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">🧾 All Sales</h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-4">
            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
            @endif

            <!-- Filters -->
            <form method="GET" class="bg-white dark:bg-gray-800 rounded-xl shadow p-4 flex flex-wrap gap-3 items-end">
                <div>
                    <label class="block text-xs text-gray-500 mb-1">Status</label>
                    <select name="status" class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm">
                        <option value="">All</option>
                        @foreach(['completed','refund_requested','refunded','cancelled'] as $s)
                            <option value="{{ $s }}" {{ request('status')===$s?'selected':'' }}>{{ ucfirst(str_replace('_',' ',$s)) }}</option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">From</label>
                    <input type="date" name="date_from" value="{{ request('date_from') }}"
                           class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm">
                </div>
                <div>
                    <label class="block text-xs text-gray-500 mb-1">To</label>
                    <input type="date" name="date_to" value="{{ request('date_to') }}"
                           class="rounded-lg border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white text-sm shadow-sm">
                </div>
                <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white text-sm rounded-lg shadow">Filter</button>
                <a href="{{ route('admin.sales.index') }}" class="px-4 py-2 text-sm text-gray-500 hover:text-gray-700">Reset</a>
            </form>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">#</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cashier</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Total</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Payment</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @php
                            $sc=['completed'=>'bg-green-100 text-green-700','refund_requested'=>'bg-yellow-100 text-yellow-700','refunded'=>'bg-red-100 text-red-700','cancelled'=>'bg-gray-100 text-gray-700'];
                        @endphp
                        @forelse($sales as $sale)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-6 py-4 text-sm text-gray-500">#{{ $sale->id }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $sale->user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $sale->customer?->name ?? '—' }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">${{ number_format($sale->total_amount, 2) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ ucfirst($sale->payment_method) }}</td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 text-xs rounded-full font-semibold {{ $sc[$sale->status] ?? 'bg-gray-100 text-gray-600' }}">
                                    {{ ucfirst(str_replace('_',' ',$sale->status)) }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $sale->created_at->format('d M Y') }}</td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.sales.show', $sale) }}" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">View</a>
                            </td>
                        </tr>
                        @empty
                        <tr><td colspan="8" class="px-6 py-10 text-center text-gray-400">No sales found.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div>{{ $sales->links() }}</div>
        </div>
    </div>
</x-app-layout>
