<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="font-sans antialiased">
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900">
        @auth
            <!-- Custom Navigation for Admin/Manager/Cashier -->
            <nav class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <a href="{{ route('dashboard') }}" class="no-underline">
                                    <div class="text-lg font-bold text-gray-800 dark:text-gray-200">
                                        @if(auth()->user()->isAdmin())
                                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">Control
                                                Center</p>
                                            <h2 class="text-2xl font-bold text-slate-900 leading-tight">
                                                {{ __('Admin Dashboard') }}
                                            </h2>

                                        @elseif(auth()->user()->isManager())
                                            <p class="text-xs font-semibold uppercase tracking-[0.2em] text-slate-500">
                                                Operations Desk</p>
                                            <h2 class="text-2xl font-bold text-slate-900 leading-tight">
                                                {{ __('Manager Dashboard') }}
                                            </h2>
                                        @else
                                            Cashier Dashboard
                                        @endif
                                    </div>
                                </a>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-10 sm:-my-px sm:ms-10 sm:flex">
                                @if(auth()->user()->isAdmin())
                                    <x-nav-link :href="route('admin.dashboard')"
                                        :active="request()->routeIs('admin.dashboard')">
                                        Dashboard
                                    </x-nav-link>
                                    <x-nav-link :href="route('admin.products.index')"
                                        :active="request()->routeIs('admin.products.*')">
                                        Products
                                    </x-nav-link>
                                    <x-nav-link :href="route('admin.suppliers.index')"
                                        :active="request()->routeIs('admin.suppliers.*')">
                                        Suppliers
                                    </x-nav-link>
                                    <x-nav-link :href="route('admin.sales.index')"
                                        :active="request()->routeIs('admin.sales.*')">
                                        Sales
                                    </x-nav-link>
                                    <x-nav-link :href="route('admin.customers.index')"
                                        :active="request()->routeIs('admin.customers.*')">
                                        Customers
                                    </x-nav-link>
                                    <x-nav-link :href="route('admin.expenses.index')"
                                        :active="request()->routeIs('admin.expenses.*')">
                                        Expenses
                                    </x-nav-link>
                                    <x-nav-link :href="route('admin.reports.sales')"
                                        :active="request()->routeIs('admin.reports.*')">
                                        Reports
                                    </x-nav-link>
                                @elseif(auth()->user()->isManager())

                                @endif
                            </div>
                        </div>

                        <!-- Settings Dropdown -->
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <x-dropdown align="right" width="48">
                                <x-slot name="trigger">
                                    <button
                                        class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">
                                        <div>{{ Auth::user()->name }}</div>
                                        <div class="ms-1">
                                            <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg"
                                                viewBox="0 0 20 20">
                                                <path fill-rule="evenodd"
                                                    d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                    clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </button>
                                </x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile')">
                                        {{ __('Profile') }}
                                    </x-dropdown-link>

                                    <!-- Authentication -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault();
                                                                                                                            this.closest('form').submit();">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>
                </div>
            </nav>
        @else
            <livewire:layout.navigation />
        @endauth

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            @if(isset($slot))
                {{ $slot }}
            @else
                @yield('content')
            @endif
        </main>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @livewireScripts

    @stack('scripts')
</body>

</html>