<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Stat Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <!-- Stat Card 1 -->
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-blue-500">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Revenue</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">
                        ${{ number_format($data['total_sales'], 2) }}</p>
                </div>

                <!-- Stat Card 2 -->
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-green-500">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Active Products</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $data['total_products'] }}</p>
                </div>

                <!-- Stat Card 3 -->
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-red-500">
                    <h3 class="text-sm font-medium text-red-500 mb-1">Low Stock Alerts</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $data['low_stock'] }}</p>
                </div>

                <!-- Stat Card 4 -->
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 border-l-4 border-purple-500">
                    <h3 class="text-sm font-medium text-gray-500 dark:text-gray-400 mb-1">Total Users</h3>
                    <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $data['total_users'] }}</p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-6">
                <a href="{{ route('admin.users.index') }}"
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                    <div class="flex items-center">
                        <div class="text-3xl mr-4">👥</div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">User Management</h3>
                            <p class="text-gray-500 dark:text-gray-400">Manage system users</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.products.index') }}"
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                    <div class="flex items-center">
                        <div class="text-3xl mr-4">📦</div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Product Management</h3>
                            <p class="text-gray-500 dark:text-gray-400">Manage inventory</p>
                        </div>
                    </div>
                </a>

                <a href="{{ route('admin.reports.sales') }}"
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6 hover:shadow-md transition">
                    <div class="flex items-center">
                        <div class="text-3xl mr-4">📊</div>
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-white">Reports</h3>
                            <p class="text-gray-500 dark:text-gray-400">View analytics</p>
                        </div>
                    </div>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>