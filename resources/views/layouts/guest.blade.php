<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'POS System') }} - Login</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased text-slate-200 bg-slate-900 selection:bg-cyan-500 selection:text-white">
    <div class="min-h-screen relative flex items-center justify-center overflow-hidden">
        <!-- Background decorative elements -->
        <div class="absolute inset-0 bg-gradient-to-br from-slate-900 via-slate-800 to-cyan-950 -z-10"></div>
        
        <div class="absolute top-0 left-0 w-full h-full overflow-hidden -z-10 pointer-events-none">
            <div class="absolute -top-[20%] -left-[10%] w-[50%] h-[50%] rounded-full bg-cyan-900/20 blur-[120px]"></div>
            <div class="absolute top-[60%] -right-[10%] w-[40%] h-[60%] rounded-full bg-cyan-800/10 blur-[100px]"></div>
        </div>

        <div class="z-10 relative w-full sm:max-w-md flex flex-col items-center">
            <!-- Brand Logo/Title Above Card -->
            <div class="mb-8 text-center">
                <a href="/" wire:navigate class="inline-flex flex-col items-center gap-3 group">
                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-cyan-500 to-cyan-700 flex items-center justify-center shadow-lg shadow-cyan-900/50 group-hover:-translate-y-1 transition-all duration-300">
                        <svg class="w-8 h-8 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                        </svg>
                    </div>
                    <span class="text-3xl font-bold text-white tracking-wide">POS <span class="text-cyan-400">System</span></span>
                </a>
            </div>

            <div class="w-full px-6 py-10 sm:px-10 bg-slate-800/50 backdrop-blur-xl shadow-2xl ring-1 ring-white/10 sm:rounded-3xl">
                {{ $slot }}
            </div>
        </div>
    </div>
</body>

</html>