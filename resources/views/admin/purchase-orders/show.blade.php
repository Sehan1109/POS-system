<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                📦 Purchase Order #{{ $purchaseOrder->id }}
            </h2>
            @if($purchaseOrder->status === 'pending')
            <form action="{{ route('admin.purchase-orders.receive', $purchaseOrder) }}" method="POST"
                  onsubmit="return confirm('Mark as received and update stock?')">
                @csrf
                <button type="submit" class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-lg shadow transition">
                    ✅ Mark as Received & Update Stock
                </button>
            </form>
            @endif
        </div>
    </x-slot>
    <div class="py-8">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @if(session('success'))
                <div class="bg-green-100 text-green-800 px-4 py-3 rounded-lg text-sm">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="bg-red-100 text-red-800 px-4 py-3 rounded-lg text-sm">{{ session('error') }}</div>
            @endif

            <!-- PO Info -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 grid grid-cols-2 md:grid-cols-4 gap-4">
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Supplier</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $purchaseOrder->supplier->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Created By</p>
                    <p class="font-semibold text-gray-900 dark:text-white">{{ $purchaseOrder->user->name }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Total Amount</p>
                    <p class="font-semibold text-indigo-600">${{ number_format($purchaseOrder->total_amount, 2) }}</p>
                </div>
                <div>
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Status</p>
                    @php $c=['pending'=>'bg-yellow-100 text-yellow-700','received'=>'bg-green-100 text-green-700','cancelled'=>'bg-red-100 text-red-700']; @endphp
                    <span class="px-2 py-1 text-xs rounded-full font-semibold {{ $c[$purchaseOrder->status] ?? '' }}">
                        {{ ucfirst($purchaseOrder->status) }}
                    </span>
                </div>
                @if($purchaseOrder->notes)
                <div class="col-span-2 md:col-span-4">
                    <p class="text-xs text-gray-500 uppercase tracking-wider mb-1">Notes</p>
                    <p class="text-gray-700 dark:text-gray-300 text-sm">{{ $purchaseOrder->notes }}</p>
                </div>
                @endif
            </div>

            <!-- Items -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <h3 class="px-6 py-4 text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider border-b border-gray-200 dark:border-gray-700">
                    Order Items
                </h3>
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Quantity</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Unit Cost</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($purchaseOrder->items as $item)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-white">{{ $item->product->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">{{ $item->quantity }}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 dark:text-gray-400">${{ number_format($item->unit_cost, 2) }}</td>
                            <td class="px-6 py-4 text-sm font-semibold text-gray-900 dark:text-white">${{ number_format($item->quantity * $item->unit_cost, 2) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- GRN History -->
            @if($purchaseOrder->goodsReceivedNotes->isNotEmpty())
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6">
                <h3 class="text-sm font-semibold text-gray-700 dark:text-gray-300 uppercase tracking-wider mb-3">Goods Received Notes</h3>
                @foreach($purchaseOrder->goodsReceivedNotes as $grn)
                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 mb-3">
                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-2">
                        Received on {{ $grn->received_at->format('d M Y H:i') }} by {{ $grn->user->name }}
                    </p>
                    <ul class="text-sm space-y-1">
                        @foreach($grn->items as $grnItem)
                        <li class="text-gray-700 dark:text-gray-300">
                            • {{ $grnItem->product->name }} — {{ $grnItem->quantity_received }} units received
                        </li>
                        @endforeach
                    </ul>
                </div>
                @endforeach
            </div>
            @endif

            <a href="{{ route('admin.purchase-orders.index') }}" class="inline-block text-sm text-indigo-600 hover:text-indigo-800">← Back to Orders</a>
        </div>
    </div>
</x-app-layout>
