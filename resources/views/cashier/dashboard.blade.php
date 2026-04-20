<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Cashier Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Revenue</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">
                        ${{ number_format($data['total_sales'], 2) }}</p>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Active Products</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $data['total_products'] }}</p>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500">
                    <h3 class="text-sm font-medium text-red-500 mb-1">Low Stock Alerts</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $data['low_stock'] }}</p>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-8 text-center mt-8">
                <div class="text-6xl mb-4">🛒</div>
                <h2 class="text-2xl font-bold mb-2 text-gray-900 dark:text-white">Ready to start selling?</h2>
                <p class="text-gray-500 dark:text-gray-400 mb-6">Open the POS terminal to process transactions and
                    accept payments.</p>
                <a href="{{ route('pos.index') }}"
                    class="inline-flex justify-center items-center px-6 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 shadow-sm transition">
                    Launch POS Terminal
                </a>
            </div>
        </div>
    </div>
</x-app-layout>