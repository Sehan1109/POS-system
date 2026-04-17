<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">📉 Stock Report</h2>
    </x-slot>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 border-l-4 border-yellow-400">
                    <p class="text-sm text-gray-500 mb-1">Low Stock Items (≤ 10)</p>
                    <p class="text-3xl font-bold text-yellow-600">{{ $lowStock }}</p>
                </div>
                <div class="bg-white dark:bg-gray-800 rounded-xl shadow p-6 border-l-4 border-red-500">
                    <p class="text-sm text-gray-500 mb-1">Out of Stock</p>
                    <p class="text-3xl font-bold text-red-600">{{ $outOfStock }}</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-xl shadow overflow-hidden">
                <h3 class="px-6 py-4 text-sm font-semibold text-gray-600 dark:text-gray-300 uppercase tracking-wider border-b border-gray-200 dark:border-gray-700">
                    Stock Levels (sorted lowest first)
                </h3>
                <table class="min-w-full divide-y divide-gray-100 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Product</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Barcode</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Stock Qty</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 dark:divide-gray-700">
                        @foreach($products as $product)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700">
                            <td class="px-6 py-4 text-sm font-medium text-gray-900 dark:text-white">{{ $product->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500">{{ $product->category->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-500 font-mono">{{ $product->barcode }}</td>
                            <td class="px-6 py-4 text-sm font-bold {{ $product->stock_quantity <= 10 ? 'text-red-600' : 'text-gray-900 dark:text-white' }}">
                                {{ $product->stock_quantity }}
                            </td>
                            <td class="px-6 py-4">
                                @if($product->stock_quantity === 0)
                                    <span class="px-2 py-1 text-xs rounded-full bg-red-100 text-red-700 font-semibold">Out of Stock</span>
                                @elseif($product->stock_quantity <= 10)
                                    <span class="px-2 py-1 text-xs rounded-full bg-yellow-100 text-yellow-700 font-semibold">Low Stock</span>
                                @else
                                    <span class="px-2 py-1 text-xs rounded-full bg-green-100 text-green-700 font-semibold">In Stock</span>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div class="px-6 py-4">{{ $products->links() }}</div>
            </div>
        </div>
    </div>
</x-app-layout>
