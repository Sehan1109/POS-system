<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>POS Terminal - NexGen</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Dark Mode Logic -->
    <script>
        if (localStorage.getItem('theme') === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    @livewireStyles
</head>
<body class="bg-gray-100 dark:bg-gray-900 overflow-hidden">
    
    @livewire('pos-screen')

    @livewireScripts
</body>
</html>