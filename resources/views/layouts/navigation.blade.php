<!-- Navigation Links -->
<div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
        {{ __('Dashboard') }}
    </x-nav-link>

    <x-nav-link :href="route('products.index')" :active="request()->routeIs('products.*')">
        {{ __('Products') }}
    </x-nav-link>

    <x-nav-link :href="route('pos.index')" :active="request()->routeIs('pos.index')">
        {{ __('POS Terminal') }}
    </x-nav-link>

    @if(auth()->check() && auth()->user()->isAdmin())
    <!-- Admin Dropdown -->
    <div class="relative" x-data="{ open: false }">
        <button @click="open = !open" @click.away="open = false"
            class="inline-flex items-center px-1 pt-1 border-b-2 text-sm font-medium leading-5 transition duration-150 ease-in-out focus:outline-none
                   {{ request()->is('admin/*') ? 'border-indigo-400 text-gray-900 dark:text-gray-100' : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300' }}">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
            </svg>
            {{ __('Admin') }}
            <svg class="ml-1 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </button>

        <div x-show="open" x-transition
             class="absolute left-0 mt-2 w-56 bg-white dark:bg-gray-800 rounded-lg shadow-xl border border-gray-200 dark:border-gray-700 z-50 py-1">

            <p class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Users & Access</p>
            <a href="{{ route('admin.users.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700">
                👥 Manage Users
            </a>
            <a href="{{ route('admin.activity-logs.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700">
                🔍 Activity Logs
            </a>

            <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
            <p class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Inventory</p>
            <a href="{{ route('admin.suppliers.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700">
                🏭 Suppliers
            </a>
            <a href="{{ route('admin.purchase-orders.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700">
                📦 Purchase Orders
            </a>

            <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
            <p class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Sales & Finance</p>
            <a href="{{ route('admin.sales.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700">
                🧾 All Sales
            </a>
            <a href="{{ route('admin.customers.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700">
                👤 Customers
            </a>
            <a href="{{ route('admin.expenses.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700">
                💸 Expenses
            </a>

            <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
            <p class="px-4 py-2 text-xs font-semibold text-gray-400 uppercase tracking-wider">Reports</p>
            <a href="{{ route('admin.reports.sales') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700">
                📊 Sales Report
            </a>
            <a href="{{ route('admin.reports.profit') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700">
                💰 Profit Report
            </a>
            <a href="{{ route('admin.reports.stock') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700">
                📉 Stock Report
            </a>
            <a href="{{ route('admin.reports.expenses') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700">
                📋 Expense Report
            </a>

            <div class="border-t border-gray-100 dark:border-gray-700 my-1"></div>
            <a href="{{ route('admin.settings.index') }}" class="flex items-center px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-gray-700">
                ⚙️ System Settings
            </a>
        </div>
    </div>
    @endif
</div>