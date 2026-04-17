<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Sale #{{ $sale->id }}</h2>
            <div class="flex gap-2">
                @if($sale->status === 'refund_requested')
                <form action="{{ route('admin.sales.refund.approve', $sale) }}" method="POST"
                      onsubmit="return confirm('Approve refund and restore stock?')">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow">✅ Approve Refund</button>
                </form>
                <form action="{{ route('admin.sales.refund.reject', $sale) }}" method="POST"
                      onsubmit="return confirm('Reject this refund request?')">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-lg shadow">❌ Reject Refund</button>
                </form>
                @endif
            </div>
        </div>
    </x-slot>
    <div class="py-8">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 text-red-800 px-4 py-3 rounded-lg text-sm">{{ session('error') }}</div>
            @endif

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 grid grid-cols-2 md:grid-cols-3 gap-4">
                <div>
                    <p class="text-xs text-gray-500 uppercase">Cashier</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $sale->user->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Customer</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $sale->customer?->name ?? 'Walk-in' }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Payment Method</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ ucfirst($sale->payment_method) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Discount</p>
                    <p class="font-semibold text-gray-900 dark:text-white">${{ number_format($sale->discount, 2) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Tax</p>
                    <p class="font-semibold text-gray-900 dark:text-white">${{ number_format($sale->tax_amount, 2) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Total</p>
                    <p class="font-bold text-indigo-600 text-lg">${{ number_format($sale->total_amount, 2) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase">Status</p>
                    @php $sc=['completed'=>'bg-green-100 text-green-700','refund_requested'=>'bg-yellow-100 text-yellow-700','refunded'=>'bg-red-100 text-red-700','cancelled'=>'bg-gray-100 text-gray-700']; @endphp
                    <span class="px-2 py-1 text-xs rounded-full font-semibold {{ $sc[$sale->status] ?? '' }}">{{ ucfirst(str_replace('_',' ',$sale->status)) }}</span>
                </div>
                @if($sale->refund_reason)
                <div class="col-span-2 md:col-span-3">
                    <p class="text-xs text-gray-500 uppercase">Refund Reason</p>
                    <p class="text-sm text-red-700 dark:text-red-400">{{ $sale->refund_reason }}</p>
                </div>
                @endif
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <h3 class="px-6 py-3 text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider border-b border-gray-200 dark:border-gray-700">Items</h3>
                <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Qty</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Price</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($sale->items as $item)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $item->product->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600">${{ number_format($item->unit_price, 2) }}</td>
                            <td class="px-6 py-4 text-sm font-semibold">${{ number_format($item->sub_total, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <a href="{{ route('admin.sales.index') }}" class="inline-block text-sm text-indigo-600 hover:text-indigo-800">← Back to Sales</a>
        </div>
    </div>
</x-app-layout>
